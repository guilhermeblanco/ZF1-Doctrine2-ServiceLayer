<?php

namespace Unit\Bisna\Service\Loader;

use Bisna\Service;

/**
 * DefaultLoaderTest
 *
 * @author Guilherme Blanco <guilhermeblanco@hotmail.com>
 */
class DefaultLoaderTest extends \PHPUnit_Framework_TestCase
{
    public function testInstantiation()
    {
        $configDir      = APPLICATION_PATH . '/configs';
        $serviceLocator = $configDir . '/serviceLocator.ini';
        $services       = $configDir . '/services.ini';
        $doctrine       = $configDir . '/doctrine.ini';

        $context   = new Service\Context\IniFileContext(array(
            'path' => $services
        ));

        $d2Config  = new \Zend_Config_Ini($doctrine, 'production');
        $container = new \Bisna\Application\Container\DoctrineContainer(
            $d2Config->resources->doctrine->toArray()
        );

        $locator   = new Service\ServiceLocator($context, $container);
        $loader    = new Service\Loader\DefaultLoader($locator);

        $this->assertType('stdClass', $loader->load('stdClass'));
    }
}