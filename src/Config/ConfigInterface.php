<?php declare(strict_types=1);


namespace App\Config;


interface ConfigInterface
{


    public function repo(): string;

    public function username(): string;

    public function ref(): string;

    public function postRollback(): string;

    public function path(): string;

    public function postDeploy(): string;

    public function host(): string;

    public function password(): ?string;

    public function private_key(): ?string;


}