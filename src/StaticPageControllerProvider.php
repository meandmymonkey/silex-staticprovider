<?php

namespace MonkeyCode\Silex\Provider;

use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;
use Silex\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * A Silex ControllerProvider to register static pages.
 *
 * @author Andreas Hucks <andreas.hucks@duochrome.net>
 */
class StaticPageControllerProvider implements ControllerProviderInterface
{
    /**
     * @var array
     */
    private $pageMap;

    /**
     * @var array
     */
    private $cacheConfig;

    /**
     * @param array $pageMap The page configuration
     * @param array $cacheConfig The cache configuration for all pages
     */
    public function __construct(
        array $pageMap = array(),
        array $cacheConfig = array()
    ) {
        $this->pageMap = $pageMap;
        $this->cacheConfig = $cacheConfig;
    }

    /**
     * {@inheritdoc}
     */
    public function connect(Application $app)
    {
        /** @var ControllerCollection $controllers */
        $controllers = $app['controllers_factory'];

        /** @var \Twig_Environment $twig */
        $twig = $app['twig'];

        foreach ($this->pageMap as $name => $config) {
            $controllers->get(
                $config['path'],
                function () use ($twig, $config) {
                    $response = new Response($twig->render($config['template']));

                    if (!empty($this->cacheConfig)) {
                        $response->setCache($this->cacheConfig);
                    }

                    if (isset($config['type'])) {
                        $response->headers->set('Content-Type', $config['type']);
                    }

                    return $response;
                }
            )->bind($name);
        }

        return $controllers;
    }
}
