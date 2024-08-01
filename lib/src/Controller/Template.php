<?php
namespace Firstbit\OneCBooking\Controller;

use Firstbit\OneCBooking\Config\Configuration;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @class Template
 * @package Firstbit\OneCBooking\Controller
 */
class Template extends Base
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function process(Request $request): Response
    {
        return new Response(file_get_contents(Configuration::getInstance()->getMainTemplateFilePath()));
    }
}