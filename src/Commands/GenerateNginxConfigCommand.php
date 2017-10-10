<?php

namespace Plekhanov\LaravelWebserverConfigGenerator\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use RuntimeException;
use Symfony\Component\Console\Question\Question;
use Plekhanov\LaravelWebserverConfigGenerator\Config;

class GenerateNginxConfigCommand extends BaseGenerateConfigCommand
{
    protected $filename = 'nginx.conf';
    
    /** \Plekhanov\LaravelWebserverConfigGenerator\Config **/
    protected $config;
    
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
            
            ->addOption('force', null, InputOption::VALUE_NONE, 'Forces generation even if the output file already exists');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $outputFile = $this->getOutputFilename($input);

        // check for existence of output file
        if (!$input->getOption('force')) {
            $this->verifyOutputFileDoesntExist($outputFile);
        }
        
        $stub = $this->getStub();
        
        $this->config = Config::initialize($input, $output, $this->getHelper('question'));
        
        $outputString = $this->replaceVariables($stub);
            
        $this->writeOutputToFile($outputString, $outputFile);
    }
    
    /**
     * Replace variables in stub contents with actual data.
     *
     * @param  string  $stub
     * @return string
     */
    protected function replaceVariables(string $stub): string
    {
        return str_replace(
            ['$SERVERNAME', '$ROOT', '$LOGSPATH', '$FASTCGIPASS'],
            [$this->config->serverName, $this->config->root, $this->config->logsPath, $this->config->fastCgiPass],
            $stub
        );
    }

    protected function writeOutputToFile($string, $file)
    {
        $result = file_put_contents($file, $string);
        
        if ($result === false) {
            throw new RuntimeException("Couldn't write to file [{$file}]");
        }
    }
}
