<?php
declare(strict_types = 1);

namespace ProxiedMail\Client\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class AbrouterServiceProvider
 * @package Abrouter\LaravelClient
 */
class ProxiedMailServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                $this->configPath() => $this->getApplicationConfigPath(),
            ], 'proxiedmail');
        }
    }

    /**
     * @return string
     */
    protected function configPath(): string
    {
        return __DIR__  . '/../../Config/proxiedmail.php';
    }
    /**
     * @return string
     */
    private function getApplicationConfigPath(): string
    {
        return $this->app->configPath('proxiedmail.php');
    }
}
