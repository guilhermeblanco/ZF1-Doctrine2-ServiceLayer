<?php

/*
 * This file bootstraps the test environment.
 */
namespace Unit;

error_reporting(E_ALL | E_STRICT);

define('APPLICATION_PATH', __DIR__ . '/../../src/application');

require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once __DIR__ . '/../../src/library/Doctrine/Common/ClassLoader.php';

$classLoader = new \Doctrine\Common\ClassLoader('Doctrine');
$classLoader->register();

$classLoader = new \Doctrine\Common\ClassLoader('Symfony');
$classLoader->register();

$classLoader = new \Doctrine\Common\ClassLoader('Zend');
$classLoader->setNamespaceSeparator('_');
$classLoader->register();

$classLoader = new \Doctrine\Common\ClassLoader('Bisna');
$classLoader->register();

$classLoader = new \Doctrine\Common\ClassLoader('Application');
$classLoader->register();

$classLoader = new \Doctrine\Common\ClassLoader('Unit');
$classLoader->register();


set_include_path(
    __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'library'
    . PATH_SEPARATOR .
    get_include_path()
);