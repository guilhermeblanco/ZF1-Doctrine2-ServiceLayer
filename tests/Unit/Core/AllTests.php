<?php

namespace Unit\Core;

use Unit\Core\Service;

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Core_AllTests::main');
}

require_once __DIR__ . '/../TestInit.php';

class AllTests
{
    public static function main()
    {
        \PHPUnit_TextUI_TestRunner::run(self::suite());
    }

    public static function suite()
    {
        $suite = new \PHPUnit_Framework_TestSuite('Core Tests');

        $suite->addTest(Service\AllTests::suite());

        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD == 'Core_AllTests::main') {
    AllTests::main();
}