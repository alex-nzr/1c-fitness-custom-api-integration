<?php
namespace Firstbit\OneCBooking\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @class Base
 * @package Firstbit\OneCBooking\Controller
 */
abstract class Base
{
    abstract public function process(Request $request): Response;
}