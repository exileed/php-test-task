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

class DeployCommand extends Command
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var Deploy
     */
    private $deploy;


    public function __construct(LoggerInterface $logger, Deploy $deploy)
    {

        $this->deploy = $deploy;
        $this->logger = $logger;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('deploy:deploy')
            ->setDescription('Prepare folder to deploy');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $config = require __DIR__ . '/../../config/config.php';

        $deploy = $this->deploy;
        $deploy->setConfig(new ArrayConfigBuilder($config[ 'deploy' ]));

        try {
            $deploy->deploy();
            $output->writeln('Deploy done');


        }catch (DeployException $e){
            $output->writeln('Deploy fail. Rollback');
            $this->logger->error($e->getMessage());

            $deploy->rollBack();

            $output->writeln(' Rollback Ok');

        }

    }

}