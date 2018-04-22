<?php declare(strict_types=1);


namespace App\Config;


class ArrayConfigBuilder implements ConfigInterface
{

    private $host;
    private $username;
    private $password;
    private $private_key;
    private $ref;
    private $repo;
    private $path;
    private $post_deploy;
    private $post_rollback;

    public function __construct(array $config)
    {
        $this->host          = $config[ 'host' ];
        $this->username      = $config[ 'user' ];
        $this->password      = $config[ 'password' ] ?? null;
        $this->private_key   = $config[ 'private_key' ] ?? null;
        $this->ref           = $config[ 'ref' ];
        $this->repo          = $config[ 'repo' ];
        $this->path          = $config[ 'path' ];
        $this->post_deploy   = $config[ 'post_deploy' ];
        $this->post_rollback = $config[ 'post_rollback' ];
    }

    public function repo(): string
    {
        return $this->repo;
    }

    public function username(): string
    {
        return $this->username;
    }

    public function ref(): string
    {
        return $this->ref;
    }

    public function postRollback(): string
    {
        return $this->post_rollback;
    }

    public function path(): string
    {
        return $this->path;
    }

    public function postDeploy(): string
    {
        return $this->post_deploy;
    }

    public function host(): string
    {
        return $this->host;
    }

    public function password(): ?string
    {
        return $this->password;
    }

    public function private_key(): ?string
    {
        return $this->private_key;
    }


}