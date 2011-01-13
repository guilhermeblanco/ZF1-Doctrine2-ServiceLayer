<?php

namespace Bisna\Service\Loader;

/**
 * DefaultLoader class.
 *
 * @author Guilherme Blanco <guilhermeblanco@hotmail.com>
 */
class DefaultLoader extends AbstractLoader implements Loader
{
    /**
     * {@inheritdoc}
     */
    public function load($class, array $options = array())
    {
        return new $class($this->locator, $options);
    }
}