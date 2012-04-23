<?php

namespace Bisna\Service\Loader;

use Bisna\Service\ServiceLocator,
    Bisna\Exception;

/**
 * LoaderManager class
 *
 * @author Guilherme Blanco <guilhermeblanco@hotmail.com>
 */
class LoaderManager
{
    /**
     * @var array Subscribed Loaders
     */
    private static $LOADERS = array(
        'default'   => 'Bisna\Service\Loader\DefaultLoader',
        'singleton' => 'Bisna\Service\Loader\SingletonLoader'
    );
    
    /**
     * @var \Bisna\Service\ServiceLocator
     */
    private $locator;

    /**
     * @var array Loaded loaders
     */
    private $instances = array();
    
    /**
     * Constructor
     * 
     * @param \Bisna\Service\ServiceLocator $locator 
     */
    public function __construct(ServiceLocator $locator)
    {
        $this->locator = $locator;
    }

    /**
     * Add a new Loader
     *
     * @param string $name
     * @param string $class
     * @return boolean
     */
    public static function addLoader($name, $class)
    {
        $originalName = $name;
        $name = mb_strtolower($name);

        if (isset(self::$LOADERS[$name])) {
            throw new Exception\NameCollisionException("Cannot override Loader entry '{$originalName}'.");
        }

        self::$LOADERS[$name] = $class;
    }

    /**
     * Override an existent Loader
     *
     * @param string $name
     * @param string $class
     */
    public static function overrideLoader($name, $class)
    {
        $name = mb_strtolower($name);
        self::$LOADERS[$name] = $class;
    }

    /**
     * Remove a subscribed loader
     *
     * @param string $name
     */
    public static function removeLoader($name)
    {
        $name = mb_strtolower($name);
        unset(self::$LOADERS[$name]);
    }

    /**
     * Retrieve a Loader based on its name.
     *
     * @param string $name
     * @return Bisna\Service\Loader\AbstractLoader
     */
    public function getLoader($name)
    {
        $originalName = $name;
        $name = mb_strtolower($name);

        if ( ! isset($this->instances[$name])) {
            // Loader is not yet loaded.
            if ( ! isset(self::$LOADERS[$name])) {
                throw new Exception\NameNotFoundException("Unable to find Loader entry '{$originalName}'.");
            }

            $loaderClass = self::$LOADERS[$name];
            $reflClass = new \ReflectionClass($loaderClass);
            
            if ( ! $reflClass->implementsInterface('Bisna\Service\Loader\Loader')) {
                throw new Exception\InvalidClassException(
                    "Loader '{$originalName}' points to '{$loaderClass}' class which does not implement Loader interface."
                );
            }

            $this->instances[$name] = new $loaderClass($this->locator);
        }

        return $this->instances[$name];
    }

    /**
     * Remove an initialized loader instance
     *
     * @param string $name
     */
    public function removeLoaderInstance($name)
    {
        $name = mb_strtolower($name);
        
        $this->instances[$name] = null;
        unset($this->instances[$name]);
    }
    
    /**
     * Remove all initialized loader instances
     */
    public function removeLoaderInstances()
    {
        $this->instances = array();
    }
}
