<?php
declare(strict_types=1);
/**
 * @package deploy-task2
 *
 * @author  Dmitriy Kuts <me@exileed.com>
 * @link    http://exileed.com
 *
 */


namespace App\SSH;


use phpseclib\Crypt\RSA;
use phpseclib\Net\SSH2;

class SSH2Client implements SSH2Connection
{

    /**
     * @var SSH2
     */
    private $connection;

    /**
     * SSH connection
     **
     * @return void
     */
    public function connect(string $host, string $login, string $password = null, string $p_key = null): void
    {
        $ssh = new SSH2($host);

        if ($p_key) {
            $key = new RSA();
            $key->loadKey($p_key);

            $connection = $ssh->login($login, $p_key);
        } else {
            $connection = $ssh->login($login, $password);
        }

        if ( ! $connection) {
            throw new \RuntimeException('SSH login failed');
        }

        $this->connection = $ssh;
    }

    /**
     * Execute command on remote server
     *
     * @param string $command
     *
     * @return string
     */
    public function exec(string $command): string
    {
        return $this->connection->exec($command);
    }

}