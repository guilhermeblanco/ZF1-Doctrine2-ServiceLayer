<?php

namespace Bisna\Application\Resource;

use Bisna\Service\ServiceLocator as BisnaServiceLocator;

/**
 * Zend Application Resource ServiceLocator class
 *
 * @author Guilherme Blanco <guilhermeblanco@hotmail.com>
 */
class Servicelocator extends \Zend_Application_Resource_ResourceAbstract
{
    /**
     * Initializes ServiceLocator Container.
     *
     * @return Bisna\Service\ServiceLocator
     */
    public function init()
    {
        $this->getBootstrap()->bootstrap('doctrine');

        $config = $this->getOptions();

        // Starting Context
        $contextClass = $config['context']['adapterClass'];
        $context = new $contextClass($config['context']['options']);
        
        // Starting ServiceLocator container
        $container = new BisnaServiceLocator($context, \Zend_Registry::get('doctrine'));

        // Add to Zend Registry
        \Zend_Registry::set('serviceLocator', $container);

        return $container;
    }
}