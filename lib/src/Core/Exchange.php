<?php
namespace Firstbit\OneCBooking\Core;

use DateTime;
use Exception;
use Firstbit\OneCBooking\Config\Configuration;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\HttpOptions;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * @class Exchange
 * @package Firstbit\OneCBooking\Core
 */
class Exchange
{
    public const LOAD_SCHEDULE_ACTION_NAME = 'loadSchedule';
    public const SEND_BOOKING_ACTION_NAME = 'sendBooking';

    protected HttpClientInterface $httpClient;

    /**
     * Exchange constructor
     * @throws \Exception
     */
    public function __construct()
    {
        $this->httpClient = HttpClient::create((new HttpOptions())
            ->setBaseUri($this->getOneCHttpServiceUrl())
            ->setHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json;charset=utf-8',
            ])
            ->setAuthBasic(
                Configuration::getInstance()->getOneCUserLogin(),
                Configuration::getInstance()->getOneCUserPassword()
            )
            ->toArray());
    }

    public function loadSchedule(DateTime $date): array
    {
        $response = $this->post(static::LOAD_SCHEDULE_ACTION_NAME, [$date]);
        return json_decode($response, true);
    }

    public function sendBooking(DateTime $date): array
    {
        $response = $this->post(static::SEND_BOOKING_ACTION_NAME, [$date]);
        return json_decode($response, true);
    }

    /**
     * @param string $endpoint
     * @param array $params
     * @return string
     */
    public function post(string $endpoint, array $params = []): string
    {
        try
        {
            $response = $this->httpClient->request('POST', $endpoint, [
                'body' => json_encode($params),
            ]);

            return $response->getContent();
        }
        catch (Exception | ExceptionInterface $e )
        {
            return json_encode(['error' => $e->getMessage()]);
        }
    }

    /**
     * @return string
     * @throws \Exception
     */
    protected function getOneCHttpServiceUrl(): string
    {
        $oneCPublicUrl = Configuration::getInstance()->getOneCPublicUrl();
        if (substr($oneCPublicUrl, -1) !== '/')
        {
            $oneCPublicUrl .= '/';
        }

        return $oneCPublicUrl . 'hs/' . Configuration::getInstance()->getOneCHttpServiceName() . '/V1/';
    }
}