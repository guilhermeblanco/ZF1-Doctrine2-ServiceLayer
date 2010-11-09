<?php

namespace Core\Service;

/**
 * AbstractService class
 *
 * @author Guilherme Blanco <guilhermeblanco@hotmail.com>
 */
abstract class AbstractService
{
    /**
     * @var Core\Service\ServiceLocator
     */
    protected $locator;

    /**
     * @var array
     */
    protected $options;
    

    /**
     * Constructor.
     *
     * @param Core\Service\ServiceLocator $locator
     * @param array $options
     */
    public final function __construct(ServiceLocator $locator, array $options = array())
    {
        $this->locator = $locator;
        $this->options = $options;
    }

    /**
     * Return Doctrine EntityManager
     *
     * @param string $emName
     * @return Doctrine\ORM\EntityManager
     */
    protected function getEntityManager($emName)
    {
        $dContainer = $this->locator->getDoctrineContainer();

        return $dContainer->getEntityManager($emName);
    }

    /**
     * Points to Loader adapter name which is used by ServiceLocator
     * to instantiate this Service class.
     *
     * @static
     * @return string
     */
    public static function getLoaderAdapterName()
    {
        return 'default';
    }

    /**
     * Returns the ServiceLocator.
     *
     * @return Core\Service\ServiceLocator
     */
    public function getServiceLocator()
    {
        return $this->locator;
    }

    /**
     * Returns the options.
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }
}