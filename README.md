StaticPageControllerProvider for Silex
======================================

A Silex ControllerProvider that helps reducing boilerplate code for
static page configuration when building small sites with Silex and Twig.

[![Build Status](https://travis-ci.org/meandmymonkey/silex-staticprovider.png?branch=master)](https://travis-ci.org/meandmymonkey/silex-staticprovider)

Usage
-----

``` php
$controllers = new StaticPageControllerProvider(
    [
        'home'     => ['path' => '/',           'template' => 'index.html.twig'],
        'about'    => ['path' => '/about',      'template' => 'team.html.twig'],
        'services' => ['path' => '/services',   'template' => 'services.html.twig'],
        'contact'  => ['path' => '/getintouch', 'template' => 'contact.html.twig']
    ]
);
```

Cache Headers
-------------

The options accepted by Symfony's ```Response::setCache()``` method can be
configured as a second argument, and will be used for all registered pages:

``` php
$controllers = new StaticPageControllerProvider(
    [
        'home' => ['path' => '/', 'template' => 'index.html.twig']
    ],
    ['s_maxage' => 3600]
);
```

Content Type
------------

An optional ```type``` parameter can be used to set the response content type:

``` php
$controllers = new StaticPageControllerProvider([
    'descr' => [
        'path' => '/descr',
        'template' => 'descr.xml.twig',
        'type' => 'text/xml',
    ]
]);
```
