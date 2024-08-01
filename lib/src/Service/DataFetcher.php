<?php
namespace Firstbit\OneCBooking\Service;

use DateTime;
use Firstbit\OneCBooking\Core\Exchange;
use Firstbit\OneCBooking\Core\Traits\Singleton;

/**
 * @class DataFetcher
 * @package Firstbit\OneCBooking\Service
 *
 * @method static DataFetcher getInstance()
 */
class DataFetcher
{
    use Singleton;

    protected Exchange $exchange;

    protected function __construct()
    {
        $this->exchange = new Exchange();
    }

    public function loadSchedule(DateTime $date): array
    {
        return $this->exchange->loadSchedule($date);
    }

    public function sendBooking(DateTime $date): array
    {
        return $this->exchange->sendBooking($date);
    }
}