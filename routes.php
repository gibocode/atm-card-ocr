<?php

require "vendor/autoload.php";
use \Gibocode\Ocr\ImageProcessor;

$request_uri = explode('?', $_SERVER['REQUEST_URI'], 2);

if ($request_uri[0] == '/' || $request_uri[0] == '/gibocode-atm-card-ocr/')
{
    require "views/index.php";
    return;
}

if ($request_uri[0] == '/process' || $request_uri[0] == '/gibocode-atm-card-ocr/process')
{
    $processor = new ImageProcessor();
    $processor->run();
}
