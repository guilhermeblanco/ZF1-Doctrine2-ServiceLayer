<?php

namespace Core\Service;

use Core\Application\Container\DoctrineContainer;

/**
 * ServiceLocator class.
 *
 * @author Guilherme Blanco <guilhermeblanco@hotmail.com>
 */
class ServiceLocator
{
    /**
     * @var Core\Service\Context\Context $context ServiceLocator Context
     */
    private $context;

    /**
     * @var Core\Application\Container\DoctrineContainer $doctrineContainer Doctrine Container
     */
    private $doctrineContainer;


    /**
     * Constructor.
     *
     * @param array $config ServiceLocator Configutarion
     */
    public function __construct(Context\Context $context, DoctrineContainer $doctrineContainer)
    {
        $this->context = $context;
        $this->doctrineContainer = $doctrineContainer;
    }

    /**
     * Loads a Service.
     *
     * @param string $name
     * @return Core\Service\AbstractService
     */
    public function getService($name)
    {
        $serviceContext = $this->context->lookup($name);
        $serviceClass   = $serviceContext['class'];
        $loaderName     = $serviceClass::getLoaderAdapterName();
        $loaderAdapter  = Loader\LoaderManager::getLoader($loaderName, $this);

        return $loaderAdapter->load($serviceClass, $serviceContext['options']);
    }

    /**
     * Returns the Doctrine Container.
     *
     * @return Core\Application\Container\DoctrineContainer
     */
    public function getDoctrineContainer()
    {
        return $this->doctrineContainer;
    }
}