<?php

namespace cisekdan\LaravelBase\Command;

use Illuminate\Console\Command;

/**
 * This command can be used to switch application environment
 * It uses symlink by default and fallbacks to copy
 * Class ChangeEnvironment
 * @package cisekdan\LaravelBase\Command
 */
class ChangeEnvironment extends Command
{
    /**
     * Default config file
     */
    const CONFIG_FILE = '.env';

    /**
     * Description of command
     * @var string
     */
    protected $description = 'Change environment of application';

    /**
     * ChangeEnvironment constructor.
     */
    public function __construct()
    {
        $this->buildSignature(self::CONFIG_FILE);
        parent::__construct();
    }

    /**
     * Task done by the command
     */
    public function handle()
    {
        $env = $this->argument('env');

        $targetFile = $this->argument('baseConfigFile');

        $sourceFile = $targetFile . '.' . $env;

        if (!$this->isValidFile($sourceFile))
        {
            $this->error("Environment file {$sourceFile} does not exist or can't be read");
            return;
        }
        if (file_exists($targetFile))
        {
            unlink($targetFile);
        }
        $result = symlink($sourceFile, $targetFile);
        if (!$result)
        {
            $result = copy($sourceFile, $targetFile);
        }
        if (!$result)
        {
            $this->error("Can't copy file {$sourceFile} as {$targetFile}");
            return;
        }
        $this->info("Environment successfully changed to {$env}");
    }

    /**
     * Function for checking whether file exists or can be read
     * @param $file
     * @return bool true if file exists and is readable, false otherwise
     */
    private function isValidFile($file)
    {
        return file_exists($file) && is_readable($file);
    }

    /**
     * Builds command signature, based on speicified environment
     * @param $environment
     */
    private function buildSignature($environment)
    {
        $this->signature = "app:environment:change
            {env: Target environment}
            {baseConfigFile={$environment}: Base config file (defaults to {$environment})}";
    }
}