<?php
use Firstbit\OneCBooking\Core\Application;
use Symfony\Component\HttpFoundation\Request;

try
{
    $composerAutoloadFile = __DIR__ . '/../vendor/autoload.php';
    if (is_file($composerAutoloadFile))
    {
        require_once $composerAutoloadFile;
    }
    else
    {
        throw new Exception('Composer file "autoload.php" not found');
    }

    $request = Request::createFromGlobals();
    if ($request->getMethod() === Request::METHOD_POST)
    {
        $response = (new Application)->handle($request);
        $response->send();
    }
    else
    {
        throw new Exception('Method not allowed', 403);
    }
}
catch (Exception $e)
{
    echo json_encode([
        'error' => $e->getMessage(),
        'code' => $e->getCode()
    ]);
}