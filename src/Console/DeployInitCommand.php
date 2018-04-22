<?php
declare(strict_types=1);
/**
 * @package deploy-task
 *
 * @author  Dmitriy Kuts <me@exileed.com>
 * @link    http://exileed.com
 *
 */


namespace App\Console;


use App\Config\ArrayConfigBuilder;
use App\Deploy;
use App\Exceptions\DeployException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeployInitCommand extends Command
{
    /**
     * @var LoggerInterface
     */
    private $logger;


    public function __construct(LoggerInterface $logger, Deploy $deploy)
    {

        $this->deploy = $deploy;
        $this->logger = $logger;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('deploy:init')
            ->setDescription('Prepare folder to deploy');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $config = require __DIR__ . '/../../config/config.php';

        $deploy = $this->deploy;
        $deploy->setConfig(new ArrayConfigBuilder($config[ 'deploy' ]));

        try {
            $deploy->init();
            $output->writeln('Okey. Deploy me');

        } catch (DeployException $e) {
            $output->writeln('Deploy init fail');
            $output->writeln($e->getMessage());

        }

    }

}