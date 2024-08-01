<?php
namespace Firstbit\OneCBooking\Core;

use Exception;
use Firstbit\OneCBooking\Controller\OneC;
use Firstbit\OneCBooking\Controller\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * @class Application
 * @package Firstbit\OneCBooking\Core
 */
class Application implements HttpKernelInterface
{
    protected Request $request;

    public function __construct()
    {
        $this->request = Request::createFromGlobals();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request|null $request
     * @param int $type
     * @param bool $catch
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(?Request $request = null, int $type = self::MAIN_REQUEST, bool $catch = true): Response
    {
        if ($request === null)
        {
            $request = $this->request;
        }

        try
        {
            switch ($request->getMethod())
            {
                case Request::METHOD_GET:
                    $response = (new Template)->process($request);
                    break;
                case Request::METHOD_POST:
                    $response = (new OneC)->process($request);
                    break;
                default:
                    $response = new Response('Method not allowed', Response::HTTP_METHOD_NOT_ALLOWED);
                    break;
            }
        }
        catch (Exception $e)
        {
            $response = new JsonResponse(
                [ "error" => "App error: ".$e->getMessage() ],
                Response::HTTP_NOT_FOUND
            );
        }

        return $response;
    }
}