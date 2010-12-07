<?php

/**
 * DefaultLoaderTest
 *
 * @author Guilherme Blanco <guilhermeblanco@hotmail.com>
 */
class DefaultLoaderTest extends \PHPUnit_Framework_TestCase
{
    public function testInstantiation()
    {
        $locator = new \stdClass();
        $loader  = new \Core\Service\Loader\DefaultLoader($locator);

        $this->assertType('stdClass', $loader->load('stdClass'));
    }
}