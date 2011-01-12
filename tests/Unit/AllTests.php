<?php

namespace Unit;

use Unit\Bisna;
//use Unit\Application;

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'AllTests::main');
}

require_once __DIR__ . '/TestInit.php';
require_once __DIR__ . '/Bisna/AllTests.php';

class AllTests
{
    public static function main()
    {
        \PHPUnit_TextUI_TestRunner::run(self::suite());
    }

    public static function suite()
    {
        $suite = new \PHPUnit_Framework_TestSuite('ZF1 D2 Service Layer Tests');
        $suite->addTest(\Unit\Bisna\AllTests::suite());
        //$suite->addTest(Application\AllTests::suite());

        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD == 'AllTests::main') {
    AllTests::main();
}