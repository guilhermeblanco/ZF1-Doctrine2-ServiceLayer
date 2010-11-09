<?php

namespace Core\Service\Context;

use Core\Service\Exception;

/**
 * IniFileContext class.
 *
 * @author Guilherme Blanco <guilhermeblanco@hotmail.com>
 */
class IniFileContext extends ContextImpl
{
    /**
     * @var array Hashmap of binded context entries.
     */
    private $map = array();


    /**
     * Constructor.
     *
     * @param array $config Context configuration
     */
    public function __construct(array $config = array())
    {
        $servicesConfig = new \Zend_Config_Ini($config['path']);
        $globalOptions = isset($config['serviceOptions'])
            ? $config['serviceOptions'] : array();
        
        foreach ($servicesConfig as $name => $options) {
            $serviceOptions = isset($options->options)
                ? array_merge($globalOptions, $options->options->toArray())
                : $globalOptions;

            $this->bind($name, $options->class, $serviceOptions);
        }
    }
}