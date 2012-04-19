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
     * @var \Bisna\Service\Context\ContextManager ServiceLocator Context Manager
     */
    private $contextManager;

    /**
     * @var \Bisna\Service\Loader\LaderManager $loaderManager Doctrine Service Loader Manager
     */
    private $loaderManager;

    /**
     * @var \Bisna\Doctrine\Container $doctrineContainer Doctrine Container
     */
    private $doctrineContainer;
    
    /**
     * @var array Global Service configuration.
     */
    private $globalConfig = array();

    /**
     * Constructor.
     *
     * @param \Bisna\Doctrine\Container $doctrineContainer Doctrine Container
     * @param array $config Service Locator Configuration
     */
    public function __construct(DoctrineContainer $doctrineContainer, array $config)
    {
        $this->contextManager    = new Context\ContextManager();
        $this->loaderManager     = new Loader\LoaderManager($this);
        
        $this->doctrineContainer = $doctrineContainer;
        
        $this->setGlobalConfig(isset($config['globalConfig']) ? $config['globalConfig'] : array());
        $this->startContexts($config);
    }

    /**
     * Retrieve the Doctrine Container.
     *
     * @return \Bisna\Doctrine\Container
     */
    public function getDoctrineContainer()
    {
        return $this->doctrineContainer;
    }

    /**
     * Retrieve internal instance of Bisna Service Context Manager.
     *
     * @return \Bisna\Service\Context\ContextManager
     */
    public function getContextManager()
    {
        return $this->contextManager;
    }
    
    /**
     * Retrieve internal instance of Bisna Service Loader Manager.
     * 
     * @return \Bisna\Service\Loader\LoaderManager
     */
    protected function getLoaderManager()
    {
        return $this->loaderManager;
    }
    
    /**
     * Retrieve Bisna Service Locator Global Services Configuration.
     * 
     * @return array
     */
    public function getGlobalConfig()
    {
        return $this->globalConfig;
    }
    
    /**
     * Define the Bisna Service Locator Global Services Configuration.
     * 
     * @param array $globalConfig 
     */
    public function setGlobalConfig(array $globalConfig)
    {
        $this->globalConfig = $globalConfig;
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
        $serviceContext = $this->contextManager->lookup($name);
        
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
        $serviceContext = $this->contextManager->lookup($name);
        
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
     * @return \Bisna\Service\Service
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
     * @return \Bisna\Service\InternalService
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
        $serviceContext = $this->contextManager->lookup($name);
        
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
     * Starts per configuration Service Locator Contexts.
     * 
     * @param array $config 
     */
    private function startContexts($config)
    {
        foreach ($config['contexts'] as $contextName => $contextConfig) {
            $contextClass   = $contextConfig['adapterClass'];
            $contextPath    = $contextConfig['path'];
            $contextOptions = isset($contextConfig['options']) ? $contextConfig['options'] : array();
            
            $this->contextManager->addContext($contextName, new $contextClass($contextPath, $contextOptions));
        }
    }

    /**
     * Loads a Service.
     *
     * @param array $serviceContext
     * @return \Bisna\Service\Service
     */
    private function loadService(array $serviceContext)
    {
        $serviceClass  = $serviceContext['class'];
        $serviceConfig = array_merge_recursive(
            $this->globalConfig, $serviceContext['config'], $serviceClass::getServiceConfiguration()
        );
        
        $loaderName    = isset($serviceConfig['loader']) ? $serviceConfig['loader'] : 'default';
        $loaderAdapter = $this->loaderManager->getLoader($loaderName);

		$options	   = isset($serviceConfig['options']) ? $serviceConfig['options'] : array(); 
		
        return $loaderAdapter->load($serviceClass, $options);
    }
}