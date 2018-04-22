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
     * @param Config\ConfigInterface $config
     */
    public function setConfig(Config\ConfigInterface $config): void
    {
        $this->config = $config;
    }


    public function init(): int
    {

        $releaseId = $this->releaseID();

        $this->connect();

        $this->ssh->exec('cd ' . $this->config->path());
        $this->createDir('releases');
        $this->logger->info('Creating deployment directory');
        $this->ssh->exec('git clone ' . $this->config->repo() . ' sources');
        $this->logger->info('Clone repo');
        $this->ssh->exec('cp -R sources releases/' . $releaseId);
        $this->ssh->exec('ln -s releases/' . $releaseId . ' current');

        return $releaseId;

    }

    public function deploy()
    {

        $releaseId = $this->releaseID();

        $this->connect();
        $this->ssh->exec('cd ' . $this->config->path());
        $this->ssh->exec('git checkout ' . $this->config->ref());
        $this->ssh->exec('git pull');

        $this->ssh->exec('cp -R sources releases/' . $releaseId);
        $this->ssh->exec('ln -s releases/' . $releaseId . ' current');

        $this->ssh->exec($this->config->postDeploy());

        return $releaseId;
    }

    private function releaseID(): int
    {
        return time();
    }

    private function connect(): void
    {
        $this->ssh->connect($this->config->host(), $this->config->username(), $this->config->password(),
            $this->config->private_key());

    }

    private function createDir($dir): void
    {
        $this->ssh->exec('mkdir ' . $dir);
    }


}