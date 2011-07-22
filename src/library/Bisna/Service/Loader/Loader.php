<?php

namespace Bisna\Service\Loader;

/**
 * Loader interface
 *
 * @author Guilherme Blanco <guilhermeblanco@hotmail.com>
 */
interface Loader
{
    /**
     * Loads a Service.
     *
     * @param string $class
     * @param array $options
     * @return Bisna\Service\Service
     */
     public function load($class, array $options = array());
}