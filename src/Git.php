<?php

namespace App\Git;

use App\SSH\SSH2Client;
use App\SSH\SSH2Connection;

/**
 * Class Git.
 */
class Git
{
    /**
     * @var string Git branch
     */
    public $branch;

    /**
     * @var string Git revision
     */
    private $revision;

    /**
     * @return string
     */
    public function getRevision(): string
    {
        return $this->revision;
    }

    /**
     * @var string Git repository
     */
    private $repo;

    /**
     * @var SSH2Client|SSH2Connection
     */
    private $ssh;



    /**
     * Git constructor.
     *
     * @param SSH2Connection $ssh
     * @param null $repo
     */
    public function __construct($repo = null, SSH2Connection $ssh = null)
    {
        $this->repo     = $repo;
        $this->branch   = $this->command('rev-parse --abbrev-ref HEAD')[ 0 ];
        $this->revision = $this->command('rev-parse HEAD')[ 0 ];
    }

    /**
     * Runs a git command and returns the output (as an array).
     *
     * @return array Lines of the output
     */
    public function command($command, $repo_path = null)
    {
        if ( ! $repo_path) {
            $repo_path = $this->repo;
        }

        // "-c core.quotepath=false" in fixes special characters issue like ë, ä, ü etc., in file names
        $command = 'git -c core.quotepath=false --git-dir="' . $repo_path . '/.git" --work-tree="' . $repo_path . '" ' . $command;

        return $this->exec($command);
    }

    /**
     * Executes a console command and returns the output (as an array).
     *
     * @param string $command Command to execute
     *
     * @return array of all lines that were output to the console during the command (STDOUT)
     */
    public function exec($ssh,$command)
    {
        $output = null;

        $ssh->exec('(' . $command . ') 2>&1', $output);

        return $output;
    }

    /**
     * Checkout given $branch.
     *
     * @param string $branch
     * @param string $repoPath
     *
     * @return array
     */
    public function checkout($branch, $repoPath = null)
    {
        $command = 'checkout ' . $branch;

        return $this->command($command, $repoPath);
    }
}