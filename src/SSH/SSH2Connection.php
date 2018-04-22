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


interface SSH2Connection
{

    public function connect(string $host, string $login, string $password = null, string $p_key = null): void;

    /**
     * Execute command on remote server
     *
     * @param string $command
     *
     * @return string
     */
    public function exec(string $command): string;

}