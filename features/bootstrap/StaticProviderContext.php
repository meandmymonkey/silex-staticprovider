<?php

namespace MonkeyCode\Silex\Provider\Behat;

use Behat\Behat\Event\ScenarioEvent;
use Behat\Behat\Event\SuiteEvent;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Mink\Driver\BrowserKitDriver;
use Behat\Mink\Exception\ExpectationException;
use Behat\Mink\Mink;
use Behat\Mink\Session;
use Behat\MinkExtension\Context\MinkContext;
use MonkeyCode\Silex\Provider\StaticPageControllerProvider;
use Silex\Application;
use Symfony\Component\HttpKernel\Client;
use Symfony\Component\Yaml\Yaml;

/**
 * Behat context to test StaticPageControllerProvider.
 *
 * @author Andreas Hucks <andreas.hucks@duochrome.net>
 */
class StaticProviderContext extends MinkContext
{
    /**
     * @var mixed A callable creating a fixture application
     */
    private static $factory;

    /**
     * @var Application The fixture application
     */
    private $app;

    /**
     * @Given /^I have registered a provider at "([^"]*)" with the config:$/
     */
    public function iHaveRegisteredAProviderAtWithTheConfig(
        $mountPoint,
        PyStringNode $config
    ) {
        $config = Yaml::parse($config);
        $cache = isset($config['cache']) ? $config['cache'] : array();
        $pages = isset($config['pages']) ? $config['pages'] : array();

        $this->app->mount(
            $mountPoint,
            new StaticPageControllerProvider($pages, $cache)
        );
    }

    /**
     * @BeforeSuite
     */
    public static function initSuite(SuiteEvent $e)
    {
        static::$factory = require_once __DIR__ . '/../fixtures/factory.php';
    }

    /**
     * @Then /^the "([^"]*)" header should contain "([^"]*)"$/
     */
    public function theHeaderShouldContain($name, $value)
    {
        $session = $this->getMink()->getSession();
        $headers = $session->getResponseHeaders();
        $name = strtolower($name);

        if (!isset($headers[$name])) {
            throw new ExpectationException(
                sprintf('No \'%s\' header present', $name),
                $session
            );
        }

        if (false === in_array($value, $headers[$name])) {
            throw new ExpectationException(
                sprintf('Header \'%s\' does not contain \'%s\'.', $name, $value),
                $session
            );
        }
    }

    /**
     * @BeforeScenario
     */
    public function initApp(ScenarioEvent $e)
    {
        $this->app = call_user_func(static::$factory);

        $mink = new Mink(
            array(
                'silex' => new Session(
                    new BrowserKitDriver(
                        new Client($this->app)
                    )
                ),
            )
        );

        $mink->setDefaultSessionName('silex');
        $this->setMink($mink);
    }
}
