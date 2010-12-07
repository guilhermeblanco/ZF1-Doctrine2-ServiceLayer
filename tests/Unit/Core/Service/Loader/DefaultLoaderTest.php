<?php

namespace Unit\Core\Service\Loader;

use Core\Service;

/**
 * DefaultLoaderTest
 *
 * @author Guilherme Blanco <guilhermeblanco@hotmail.com>
 */
class DefaultLoaderTest extends \PHPUnit_Framework_TestCase
{
    public function testInstantiation()
    {
        $iniFile  = __DIR__ . '/../../../../../src/application/configs/services.ini'; // include_path

        $config   = new \Zend_Config_Ini($iniFile);
        $context  = new Service\Context\IniFileContext(array(
            'path' => $iniFile
        ));
        $doctrine = new 
        $locator  = new Service\ServiceLocator($context, $doctrine);
        $loader   = new Service\Loader\DefaultLoader($locator);

        $this->assertType('stdClass', $loader->load('stdClass'));
    }
}