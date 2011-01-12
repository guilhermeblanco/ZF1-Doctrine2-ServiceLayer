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
     * @param Core\Service\Context\Context $context ServiceLocator Context
     * @param Core\Application\Container\DoctrineContainer $doctrineContainer Doctrine Container
     */
    public function __construct(Context\Context $context, DoctrineContainer $doctrineContainer)
    {
        $this->context = $context;
        $this->doctrineContainer = $doctrineContainer;
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

    /**
     * Loads an external Service.
     *
     * @param string $name External service name
     * @return Core\Service\AbstractService
     */
    public function getService($name)
    {
        $serviceContext = $this->context->lookup($name);
        $serviceConfig  = $serviceContext['config'];

        // Throw exception if service is internal
        if (isset($serviceConfig['internal']) && $serviceConfig['internal']) {
            throw new Exception\InvalidServiceException(
                "Unable to initialize internal service '{$serviceContext['class']}' through an external call."
            );
        }

        return $this->loadService($serviceContext);
    }

    /**
     * Loads an internal Service.
     *
     * @param string $name Internal service name
     * @return Core\Service\AbstractService
     */
    public function getInternalService($name)
    {
        $serviceContext = $this->context->lookup($name);
        $serviceConfig  = $serviceContext['config'];

        // Throw exception if service is not internal
        if ( ! (isset($serviceConfig['internal']) && $serviceConfig['internal'])) {
            throw new Exception\InvalidServiceException(
                "Unable to initialize external service '{$serviceContext['class']}' through an internal call."
            );
        }

        return $this->loadService($serviceContext);
    }

    /**
     * Loads a Service.
     *
     * @param array $serviceContext
     * @return Core\Service\AbstractService
     */
    private function loadService(array $serviceContext)
    {
        $serviceClass  = $serviceContext['class'];
        $serviceConfig = $serviceContext['config'];
        
        $loaderName    = isset($serviceConfig['loader']) ? $serviceConfig['loader'] : 'default';
        $loaderAdapter = Loader\LoaderManager::getLoader($serviceConfig['loader'], $this);

        return $loaderAdapter->load($serviceClass, $serviceConfig['options']);
    }
}
