<?php declare(strict_types=1);

namespace Swpa\CustomOptions\Service;

use Shopware\Core\System\SystemConfig\SystemConfigService;

class Config
{

    private $systemConfig;

    const
        SYSTEM_CONFIG_DEBUG = 'CustomOptions.settings.debug';


    public function __construct(SystemConfigService $configService)
    {
        $this->systemConfig = $configService;
    }

    public function debug(): bool
    {

        return $this->systemConfig->get(static::SYSTEM_CONFIG_DEBUG) == true;
    }
}
