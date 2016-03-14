<?php

namespace cisekdan\LaravelBase;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * @var array List of commands to register
     */
    private $commands = [
        'cisekdan\LaravelBase\Command\ChangeEnvironment'
    ];

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->registerCommands($this->commands);
    }

    /**
     * Registers provided commands
     * @param $commands List of commands to register
     */
    private function registerCommands($commands)
    {
        $this->commands($commands);
    }
}