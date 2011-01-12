<?php

namespace Core\Service\Loader;

/**
 * AbstractLoader class.
 *
 * @author Guilherme Blanco <guilhermeblanco@hotmail.com>
 */
abstract class AbstractLoader
{
    /**
     * @var Core\Service\ServiceLocator
     */
    protected $locator;

    /**
     * Constructor.
     *
     * @param Core\Service\ServiceLocator $locator ServiceLocator
     */
    public function __construct(\Core\Service\ServiceLocator $locator)
    {
        $this->locator = $locator;
    }
}
