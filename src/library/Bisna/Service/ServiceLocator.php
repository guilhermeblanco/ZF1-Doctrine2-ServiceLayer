<?php

namespace Bisna\Service;

use Bisna\Doctrine\Container as DoctrineContainer,
    Bisna\Exception;

/**
 * ServiceLocator class.
 *
 * @author Guilherme Blanco <guilhermeblanco@hotmail.com>
 */
class ServiceLocator
{
    /**
     * @var Bisna\Service\Context\Context $context ServiceLocator Context
     */
    private $context;

    /**
     * @var Bisna\Doctrine\Container $doctrineContainer Doctrine Container
     */
    private $doctrineContainer;
    
    /**
     * @var Bisna\Service\Loader\LaderManager $loaderManager Doctrine Service Loader Manager
     */
    private $loaderManager;

    /**
     * Constructor.
     *
     * @param Bisna\Service\Context\Context $context ServiceLocator Context
     * @param Bisna\Doctrine\Container $doctrineContainer Doctrine Container
     */
    public function __construct(Context\Context $context, DoctrineContainer $doctrineContainer)
    {
        $this->loaderManager     = new Loader\LoaderManager($this);
        $this->doctrineContainer = $doctrineContainer;
        $this->context           = $context;
    }

    /**
     * Returns the Doctrine Container.
     *
     * @return Bisna\Doctrine\Container
     */
    public function getDoctrineContainer()
    {
        return $this->doctrineContainer;
    }

    /**
     * Returns the Service Context.
     *
     * @return Bisna\Service\Context\Context
     */
    public function getContext()
    {
        return $this->context;
    }
    
    /**
     * Checks if a given service name is currently mapped as a service in ServiceLocator.
     * 
     * @param string $name Service name
     * 
     * @return boolean
     */
    public function hasService($name)
    {
        $serviceContext = $this->context->lookup($name);
        
        if ($serviceContext === null) {
            return false;
        }
        
        $classParents = class_parents($serviceContext['class']);
        
        return in_array('Bisna\Service\Service', $classParents) && 
             ! in_array('Bisna\Service\InternalService', $classParents);
    }
    
    /**
     * Checks if a given service name is currently mapped as an internal service in ServiceLocator.
     * 
     * @param string $name Service name
     * 
     * @return boolean
     */
    public function hasInternalService($name)
    {
        $serviceContext = $this->context->lookup($name);
        
        if ($serviceContext === null) {
            return false;
        }
        
        $classParents = class_parents($serviceContext['class']);
        
        return in_array('Bisna\Service\InternalService', $classParents);
    }

    /**
     * Loads an external Service.
     *
     * @param string $name External service name
     * @return Bisna\Service\Service
     */
    public function getService($name)
    {
        $serviceContext = $this->getServiceContext($name);
        $classParents   = class_parents($serviceContext['class']);
        
        // Throw exception if service is internal
        if (in_array('Bisna\Service\InternalService', $classParents)) {
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
     * @return Bisna\Service\InternalService
     */
    public function getInternalService($name)
    {
        $serviceContext = $this->getServiceContext($name);
        $classParents   = class_parents($serviceContext['class']);
        
        // Throw exception if service is not internal
        if ( ! in_array('Bisna\Service\InternalService', $classParents)) {
            throw new Exception\InvalidServiceException(
                "Unable to initialize external service '{$serviceContext['class']}' through an internal call."
            );
        }

        return $this->loadService($serviceContext);
    }
    
    /**
     * Retrieve context of a given service name.
     * 
     * @param string $name Service name
     * 
     * @return array
     */
    private function getServiceContext($name)
    {
        $serviceContext = $this->context->lookup($name);
        
        // Throw an exception if service not found
        if ( ! is_array($serviceContext)) {
            throw new Exception\InvalidServiceException(
                "Unable to locate service '".$name."'."
            );
        }

        $classParents = class_parents($serviceContext['class']);
        
        // Throw exception if service is not service
        if ( ! in_array('Bisna\Service\Service', $classParents)) {
            throw new Exception\InvalidServiceException(
                "Unable to initialize a non service '{$serviceContext['class']}'."
            );
        }
        
        return $serviceContext;
    }
    
    /**
     * Retrieve internal instance of Bisna Service Loader Manager.
     * 
     * @return Bisna\Service\Loader\LoaderManager
     */
    protected function getLoaderManager()
    {
        return $this->loaderManager;
    }

    /**
     * Loads a Service.
     *
     * @param array $serviceContext
     * @return Bisna\Service\Service
     */
    private function loadService(array $serviceContext)
    {
        $serviceClass  = $serviceContext['class'];
        $serviceConfig = $serviceContext['config'];
        
        $loaderName    = isset($serviceConfig['loader']) ? $serviceConfig['loader'] : 'default';
        $loaderAdapter = $this->getLoaderManager()->getLoader($loaderName);

		$options	   = isset($serviceConfig['options']) ? $serviceConfig['options'] : array(); 
		
        return $loaderAdapter->load($serviceClass, $options);
    }
}