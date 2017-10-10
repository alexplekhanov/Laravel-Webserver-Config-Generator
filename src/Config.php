<?php

namespace Plekhanov\LaravelWebserverConfigGenerator;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class Config
{
    protected $helper;
    
    /** \Symfony\Component\Console\Input\InputInterface **/
    protected $input;
    
    /** \Symfony\Component\Console\Input\OutputInterface **/
    protected $output;
    
    /** string **/
    protected $serverName;
    
    /** string **/
    protected $root;
    
    /** string **/
    protected $logsPath;
    
    /** string **/
    protected $fastCgiPass;
    
    public function __construct(InputInterface $input, OutputInterface $output, $helper)
    {
        $this->helper = $helper;
        $this->input = $input;
        $this->output = $output;
        
        $this->initializeServerName()
            ->initializeRoot()
            ->initializeLogsPath()
            ->initializeFastCgiPass();
    }
    
    public static function initialize(InputInterface $input, OutputInterface $output, $helper)
    {
        return new static($input, $output, $helper);
    }
    
    protected function initializeServerName(): self
    {
        $this->serverName = $this->initializeVariable('Please enter the server name (e.g. google.com)');
        
        return $this;
    }

    protected function initializeRoot(): self
    {
        $this->root = $this->initializeVariable('Please enter the document root path');
        
        return $this;
    }
    
    protected function initializeLogsPath(): self
    {
        $this->logsPath = $this->initializeVariable('Please enter the path for logs');
        
        return $this;
    }
    
    protected function initializeFastCgiPass(): self
    {
        $this->fastCgiPass = $this->initializeVariable('Please enter the fastcgi address');
        
        return $this;
    }
    
    protected function initializeVariable($question)
    {
        $question = new Question($question . "\n");

        return $this->helper->ask($this->input, $this->output, $question);
    }
    
    public function get($property)
    {
        if (!property_exists($this, $property)) {
            throw new RuntimeException("Couldn't find [$property] in Config");
        }
        
        return $this->$property;
    }
    
    public function __get($name)
    {
        return $this->get($name);
    }
}