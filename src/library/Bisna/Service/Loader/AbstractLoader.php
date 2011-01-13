<?php

namespace Bisna\Service\Loader;

/**
 * AbstractLoader class.
 *
 * @author Guilherme Blanco <guilhermeblanco@hotmail.com>
 */
abstract class AbstractLoader
{
    /**
     * @var Bisna\Service\ServiceLocator
     */
    protected $locator;

    /**
     * Constructor.
     *
     * @param Bisna\Service\ServiceLocator $locator ServiceLocator
     */
    public function __construct(\Bisna\Service\ServiceLocator $locator)
    {
        $this->locator = $locator;
    }
}
