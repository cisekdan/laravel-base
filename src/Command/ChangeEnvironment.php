<?php
/**
 * Created by PhpStorm.
 * User: Daniel
 * Date: 14.03.2016
 * Time: 20:38
 */

namespace cisekdan\LaravelBase\Command;

use Illuminate\Console\Command;

class ChangeEnvironment
{
    const CONFIG_FILE = '.env';

    protected $description = 'Change environment of application';

    public function __construct()
    {
        $this->buildSignature(self::CONFIG_FILE);
    }

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

    private function isValidFile($file)
    {
        return file_exists($file) && is_readable($file);
    }

    private function buildSignature($environment)
    {
        $this->signature = "app:environment:change
            {env: Target environment}
            {baseConfigFile={$environment}: Base config file (defaults to {$environment)}";
    }
}