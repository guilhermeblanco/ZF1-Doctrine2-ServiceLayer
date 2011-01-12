<?php

namespace Unit\Bisna\Service\Loader;

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Bisna_Service_Loader_AllTests::main');
}

require_once __DIR__ . '/../../../TestInit.php';

class AllTests
{
    public static function main()
    {
        \PHPUnit_TextUI_TestRunner::run(self::suite());
    }

    public static function suite()
    {
        $suite = new \PHPUnit_Framework_TestSuite('Bisna Service Loader Tests');

        $suite->addTestSuite('Unit\Bisna\Service\Loader\DefaultLoaderTest');

        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD == 'Bisna_Service_Loader_AllTests::main') {
    AllTests::main();
}