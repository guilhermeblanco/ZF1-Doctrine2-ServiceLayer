<?php

namespace Bisna\Service\Loader;

/**
 * SingletonLoader class.
 *
 * @author Guilherme Blanco <guilhermeblanco@hotmail.com>
 */
class SingletonLoader extends AbstractLoader implements Loader
{
    /**
     * @var array
     */
    private static $instances = array();

    /**
     * {@inheritdoc}
     */
    public function load($class, array $options = array())
    {
        if ( ! isset(self::$instances[$class])) {
            $service = new $class($this->locator, $options);

            self::$instances[$class] = $service;
        }

        return self::$instances[$class];
    }
}