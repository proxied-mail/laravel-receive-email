<?php
declare(strict_types = 1);

namespace ProxiedMail\Client\Bridge;

use Illuminate\Config\Repository as ConfigRepository;
use ProxiedMail\Client\Config\Config;
use ProxiedMail\Client\Entrypoint\PxdMailApinitializer;
use ProxiedMail\Client\Facades\ApiFacade;

class ProxiedMailClient
{
    /**
     * @var ConfigRepository
     */
    private $configRepository;

    /**
     * @var ApiFacade
     */
    private $client;

    public function __construct(ConfigRepository $repository)
    {
        $this->configRepository = $repository;
        $this->client = PxdMailApinitializer::init(
            new Config(
                $this->configRepository->get('proxiedmail.host'),
                $this->configRepository->get('proxiedmail.apiToken')
            )
        );
    }

    public function getClient(): ApiFacade
    {
        return $this->client;
    }
}
