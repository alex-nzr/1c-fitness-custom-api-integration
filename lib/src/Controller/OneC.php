<?php
namespace Firstbit\OneCBooking\Controller;

use DateTime;
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
        $action = $request->get('action');
        if (!empty($action) && method_exists(static::class, $action))
        {
            $params = [];//todo extract from request
            $result = $this->$action($params);
        }
        return new JsonResponse($result);
    }

    /**
     * @throws \Exception
     */
    public function loadSchedule(array $params): array
    {
        $date = new DateTime($params['dateFromParams']);
        return DataFetcher::getInstance()->loadSchedule($date);
    }
}