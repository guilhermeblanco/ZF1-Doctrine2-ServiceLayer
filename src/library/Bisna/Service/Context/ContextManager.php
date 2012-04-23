<?php

namespace Bisna\Service\Context;

use Bisna\Exception;

/**
 * ContextManager class.
 *
 * @author Guilherme Blanco <guilhermeblanco@hotmail.com>
 */
class ContextManager
{
    /**
     * @var array Hashmap of added Context entries.
     */
    private $contexts = array();
    
    /**
     * Add a new Context.
     * 
     * @param string $name
     * @param Context $context 
     */
    public function addContext($name, Context $context)
    {
        $originalName = $name;
        $name = mb_strtolower($name);

        if (isset($this->contexts[$name])) {
            throw new Exception\NameCollisionException("Cannot override Context entry '{$originalName}'.");
        }

        $this->contexts[$name] = $context;
    }

    /**
     * Override an existent Context
     *
     * @param string $name
     * @param Context $context 
     */
    public static function overrideContext($name, Context $context)
    {
        $name = mb_strtolower($name);
        $this->contexts[$name] = $context;
    }

    /**
     * Remove a subscribed Context
     *
     * @param string $name
     */
    public static function removeContext($name)
    {
        $name = mb_strtolower($name);
        unset($this->contexts[$name]);
    }
    
    /**
     * Retrieve a Context based on its name.
     *
     * @param string $name
     * 
     * @return \Bisna\Service\Context\Context
     */
    public function getContext($name)
    {
        $originalName = $name;
        $name = mb_strtolower($name);

        if ( ! isset($this->contexts[$name])) {
            throw new Exception\NameNotFoundException("Unable to find Context entry '{$originalName}'.");
        }
        
        return $this->contexts[$name];
    }
    
    /**
     * Returns the configuration bound to the specified name in context manager.
     *
     * @param string $name
     * 
     * @return array
     */
    public function lookup($name)
    {
        foreach ($this->contexts as $context) {
            $serviceContext = $context->lookup($name);
            
            if ($serviceContext !== null) {
                return $serviceContext;
            }
        }
        
        return null;
    }
}