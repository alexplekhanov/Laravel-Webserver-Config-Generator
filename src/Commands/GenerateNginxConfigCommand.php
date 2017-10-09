<?php

namespace Plekhanov\LaravelWebserverConfigGenerator\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateNginxConfigCommand extends BaseGenerateConfigCommand
{
    protected $filename = 'nginx.conf';
    
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('generate:nginx')
    
            // the short description shown while running "php bin/console list"
            ->setDescription('Generate config for Nginx webserver')
    
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to generate Nginx webserver for Laravel project.')
            
            ->addOption('output', 'o', InputOption::VALUE_REQUIRED, 'Output file')
            
            ->addOption('force', null, InputOption::VALUE_NONE, 'Forces generation even if the output file already exists')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output = $this->getOutputFilename($input);
        
        // check for existence of output file
        if (!$input->getOption('force')) {
            $this->verifyOutputFileDoesntExist($output);
        }
    }
}
