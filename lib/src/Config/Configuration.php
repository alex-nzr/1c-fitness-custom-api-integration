<?php
namespace Firstbit\OneCBooking\Config;

use Exception;
use Firstbit\OneCBooking\Core\Traits\Singleton;

/**
 * @class Configuration
 * @package Firstbit\OneCBooking\Config
 *
 * @method static Configuration getInstance()
 */
class Configuration
{
    use Singleton;

    /**
     * @return string
     */
    public static function getMainTemplateFilePath(): string
    {
        return __DIR__ . '/../../templates/main/index.html';
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getOneCUserLogin(): string
    {
        if (!defined('ONE_C_FITNESS_INTEGRATION_LOGIN'))
        {
            throw new Exception('1c login constant not defined');
        }

        return ONE_C_FITNESS_INTEGRATION_LOGIN;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getOneCUserPassword(): string
    {
        if (!defined('ONE_C_FITNESS_INTEGRATION_PASSWORD'))
        {
            throw new Exception('1c password constant not defined');
        }

        return ONE_C_FITNESS_INTEGRATION_PASSWORD;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getOneCPublicUrl(): string
    {
        if (!defined('ONE_C_FITNESS_INTEGRATION_URL'))
        {
            throw new Exception('1c public url constant not defined');
        }

        return ONE_C_FITNESS_INTEGRATION_URL;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getOneCHttpServiceName(): string
    {
        if (!defined('ONE_C_FITNESS_INTEGRATION_HTTP_SERVICE_NAME'))
        {
            throw new Exception('1c http-service name constant not defined');
        }
        return ONE_C_FITNESS_INTEGRATION_HTTP_SERVICE_NAME;
    }
}