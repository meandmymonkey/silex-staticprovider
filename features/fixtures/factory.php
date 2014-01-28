<?php

use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;

return function($pageConfig = array(), $mountPoint = '/')
{
    $app = new Application();

    $app['debug'] = true;
    $app->register(new UrlGeneratorServiceProvider());
    $app->register(
        new TwigServiceProvider(),
        array(
            'twig.path' => __DIR__ . '/views',
            'twig.options' => array(
                'debug'=> $app['debug']
            )
        )
    );

    return $app;
};
