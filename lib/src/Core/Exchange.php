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

    /**
     * @param \DateTime $date
     * @return array
     */
    public function loadSchedule(DateTime $date): array
    {
        $response = '{
          "services": {
            "111": {
              "title": "Бронь шезлонгов",
              "slots": {
                "morning": 11,
                "evening": 50
              },
              "maxGuestCount": 15
            },
            "222": {
              "title": "Бронь кабанос",
              "slots": {
                "morning": 5,
                "evening": 0
              },
              "maxGuestCount": 12
            },
            "333": {
              "title": "Бронь ракушек",
              "slots": {
                "morning": 12,
                "evening": 12
              },
              "maxGuestCount": 11
            }
          }
        }';
        sleep(3);

        //$response = $this->post(static::LOAD_SCHEDULE_ACTION_NAME, ['date' => $date->format('d.m.Y')]);
        return $this->prepareResponse($response);
    }

    /**
     * @param \DateTime $date
     * @return array
     */
    public function sendBooking(DateTime $date): array
    {
        $response = '{"success":true}';
        sleep(3);
        //$response = $this->post(static::SEND_BOOKING_ACTION_NAME, [$date]);
        return $this->prepareResponse($response);
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

    /**
     * @param string $response
     * @return string[]
     */
    protected function prepareResponse(string $response): array
    {
        $responseData = json_decode($response, true);
        if (!is_array($responseData))
        {
            $responseData = [
                'error' => 'Error on decoding response'
            ];
        }
        elseif (!key_exists('error', $responseData))
        {
            $responseData = [
                'success' => true,
                'data' => $responseData
            ];
        }

        return $responseData;
    }
}