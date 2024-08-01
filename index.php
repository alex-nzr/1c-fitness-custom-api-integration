<?php
use Firstbit\OneCBooking\Core\Application;

try
{
    $composerAutoloadFile = __DIR__ . '/lib/vendor/autoload.php';
    if (is_file($composerAutoloadFile))
    {
        require_once $composerAutoloadFile;
    }
    else
    {
        throw new Exception('Composer file "autoload.php" not found');
    }

    $response = (new Application)->handle();
    $response->send();
}
catch (Exception $e)
{
    echo $e->getMessage();
}