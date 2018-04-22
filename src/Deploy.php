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

    public function __construct(LoggerInterface $logger, SSH2Connection $ssh)
    {
        $this->ssh = $ssh;
    }

    /**
     * @param Config\ConfigInterface $config
     */
    public function setConfig(Config\ConfigInterface $config): void
    {
        $this->config = $config;
    }


    public function init()
    {


        $this->ssh->exec('');

    }

    private function connect(): void
    {
        $this->ssh->connect($this->config->host(), $this->config->username(), $this->config->password(),
            $this->config->private_key());

    }


}