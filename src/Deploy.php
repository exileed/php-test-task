<?php
declare(strict_types=1);
/**
 * @package deploy-task2
 *
 * @author  Dmitriy Kuts <me@exileed.com>
 * @link    http://exileed.com
 *
 */


namespace App;


use App\SSH\SSH2Client;
use App\SSH\SSH2Connection;
use Psr\Log\LoggerInterface;

class Deploy
{

    /**
     * @var Config\ConfigInterface
     */
    private $config;

    /**
     * @var SSH2Client
     */
    private $ssh;

    private $revision;
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger, SSH2Connection $ssh)
    {
        $this->logger = $logger;
        $this->ssh    = $ssh;
    }

    /**
     * @return mixed
     */
    public function getRevision()
    {
        return $this->revision;
    }

    /**
     * @param mixed $revision
     */
    public function setRevision($revision): void
    {
        $this->revision = $revision;
    }

    /**
     * @param Config\ConfigInterface $config
     */
    public function setConfig(Config\ConfigInterface $config): void
    {
        $this->config = $config;
    }


    public function init(): string
    {

        $this->connect();
        $releaseId = $this->currentReleaseID();
        $this->exec('cd ' . $this->config->path());
        $this->createDir('releases');
        $this->logger->info('Creating deployment directory');
        $this->exec('git clone ' . $this->config->repo() . ' sources');
        $this->logger->info('Clone repo');
        $this->exec('cp -R sources releases/' . $releaseId);
        $this->exec('ln -s releases/' . $releaseId . ' current');

        return $releaseId;

    }

    private function connect(): void
    {
        $this->ssh->connect($this->config->host(), $this->config->username(), $this->config->password(),
            $this->config->private_key());

    }

    private function currentReleaseID(): string
    {
        $sources = $this->config->path() . '/sources';

        return $this->exec('rev-parse HEAD', $sources);
    }

    private function exec(string $command, string $path = null)
    {
        $path = $path ?? $this->config->path();

        return $this->ssh->exec('cd ' . $path . '; ' . $command);
    }

    private function createDir($dir): void
    {
        $this->exec('mkdir ' . $dir);
    }

    public function deploy(): string
    {

        $this->connect();
        $releaseId = $this->currentReleaseID();

        $this->exec('cd ' . $this->config->path() . '/sources');
        $this->exec('git checkout ' . $this->config->ref());
        $this->exec('git reset --hard HEAD');
        $this->exec('git pull');
        $this->exec('cd ' . $this->config->path());
        $this->exec('cp -R sources releases/' . $releaseId);
        $this->exec('ln -s releases/' . $releaseId . ' current');

        $this->exec($this->config->postDeploy());

        return $releaseId;
    }

    public function rollBack(string $releaseId = null)
    {

        if ( ! $releaseId) {
            $releaseId = $this->oldReleaseID();
        }

        $this->exec('ln -s releases/' . $releaseId . ' current');

        $this->exec($this->config->postRollback());

    }

    private function oldReleaseID(): string
    {
        $sources = $this->config->path() . '/sources';

        return $this->exec('rev-parse HEAD~1', $sources);
    }


}