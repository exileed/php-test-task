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
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RollBackCommand extends Command
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
            ->setName('deploy:rollback')
            ->addArgument('id', InputArgument::OPTIONAL, 'rollback')
            ->setDescription('Prepare folder to deploy');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $config = require __DIR__ . '/../../config/config.php';

        $deploy = $this->deploy;
        $deploy->setConfig(new ArrayConfigBuilder($config[ 'deploy' ]));

        if ($input->getArgument('id') === null) {
            $deploy->rollBack();
        } else {
            $deploy->rollBack($input->getArgument('id'));
        }

        $output->writeln('Deploy done');

    }

}