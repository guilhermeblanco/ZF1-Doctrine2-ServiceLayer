<?php

namespace Bisna\Service\Context;

use Bisna\Service\Exception;

/**
 * IniFileContext class.
 *
 * @author Guilherme Blanco <guilhermeblanco@hotmail.com>
 */
class IniFileContext extends ContextImpl
{
    /**
     * Constructor.
     *
     * @param array $config Context configuration
     */
    public function __construct($path, array $config = array())
    {
        $servicesConfig = new \Zend_Config_Ini($path);
        
        foreach ($servicesConfig as $name => $config) {
            $serviceClass  = $config->class;
            $serviceConfig = $config->toArray();
            
            // Do not allow 'class' config entry
            unset($serviceConfig['class']);

            $this->bind($name, $serviceClass, $serviceConfig);
        }
    }
}
