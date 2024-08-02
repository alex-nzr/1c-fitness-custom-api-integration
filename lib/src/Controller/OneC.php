<?php
namespace Firstbit\OneCBooking\Controller;

use DateTime;
use Exception;
use Firstbit\OneCBooking\Service\DataFetcher;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @class OneC
 * @package Firstbit\OneCBooking\Controller
 */
class OneC extends Base
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function process(Request $request): Response
    {
        $result = [];
        $params = $this->preparePostData($request->getContent());
        $action = (string)$params['action'];
        if (!empty($action) && method_exists(static::class, $action))
        {
            $result = $this->$action($params);
        }
        return new JsonResponse($result);
    }

    /**
     * @throws \Exception
     */
    public function loadSchedule(array $params): array
    {
        if (!empty($params['date']))
        {
            $date = DateTime::createFromFormat('d.m.Y', $params['date']);
            if ($date instanceof DateTime)
            {
                return DataFetcher::getInstance()->loadSchedule($date);
            }
            else
            {
                throw new Exception('Can not create date object from param - ' . $params['date']);
            }
        }
        else
        {
            throw new Exception('Date param is empty');
        }
    }

    /**
     * @throws \Exception
     */
    public function sendBooking(array $params): array
    {
        $date = new DateTime($params['dateFromParams']);
        return DataFetcher::getInstance()->sendBooking($date);
    }

    protected function preparePostData($params): array
    {
        $postData = json_decode($params,true);
        $postParams = is_array($postData) ? $postData : [];
        //return Utils::cleanRequestData($postParams);
        return $postParams;
    }
}