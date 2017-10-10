<?php

namespace Plekhanov\LaravelWebserverConfigGenerator\Commands;

use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;

abstract class BaseGenerateConfigCommand extends Command
{
    /**
     * Get output config filename.
     *
     * @param  \Symfony\Component\Console\Input\InputInterface  $input
     * @return string
     */
    protected function getOutputFilename(InputInterface $input)
    {
        $outputOption = $input->getOption('output');
        
        if (!is_null($outputOption)) {
            return $outputOption;
        }
        
        $currentWorkingDirectory = getcwd();
        
        if ($currentWorkingDirectory === false) {
            throw new RuntimeException('Unknown issue with filesystem.');
        }
        
        return $currentWorkingDirectory . '/' . $this->filename;
    }
    
    /**
     * Verify that the application does not already exist.
     *
     * @param  string  $outputFile
     * @return void
     */
    protected function verifyOutputFileDoesntExist($outputFile)
    {
        if (is_dir($outputFile) || is_file($outputFile)) {
            throw new RuntimeException("File [{$outputFile}] already exists!");
        }
    }
    
    /**
     * Get stub file contents.
     *
     * @return string
     */
    protected function getStub(): string
    {
        return file_get_contents(__DIR__ . "/../stubs/{$this->filename}.stub");
    }
}
