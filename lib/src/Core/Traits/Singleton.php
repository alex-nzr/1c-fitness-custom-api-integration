<?php
namespace Firstbit\OneCBooking\Core\Traits;

/**
 * @trait Singleton
 * @package Firstbit\OneCBooking\Core\Traits
 */
trait Singleton
{
    protected static $instance = null;

    /**
     * @return mixed
     */
    public static function getInstance()
    {
        if (empty(static::$instance))
        {
            static::$instance = new static();
        }
        return static::$instance;
    }

    protected function __construct(){}

    /**
     * @return void
     */
    final public function __clone()
    {
    }

    /**
     * @return void
     */
    final public function __wakeup()
    {
    }

    /**
     * @return void
     */
    final public function __sleep()
    {
    }
}