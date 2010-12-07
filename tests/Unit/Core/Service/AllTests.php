<?php

namespace Unit\Core\Service;

use Unit\Core\Service\Loader;

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Core_Service_AllTests::main');
}

require_once __DIR__ . '/../../TestInit.php';

class AllTests
{
    public static function main()
    {
        \PHPUnit_TextUI_TestRunner::run(self::suite());
    }

    public static function suite()
    {
        $suite = new \PHPUnit_Framework_TestSuite('Core Service Tests');

        $suite->addTest(Loader\AllTests::suite());

        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD == 'Core_Service_AllTests::main') {
    AllTests::main();
}