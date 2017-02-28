<?php
namespace Skeletor\Manager;

use League\CLImate\CLImate;

abstract class Manager
{
    protected $cli;
    public $options;

    public function __construct(CLImate $cli, array $options)
    {
        $this->cli = $cli;
        $this->options = $options;
    }
}