<?php

namespace Bisna\Service;

/**
 * Service class
 *
 * @author Guilherme Blanco <guilhermeblanco@hotmail.com>
 */
abstract class Service
{
    /**
     * @var \Bisna\Service\ServiceLocator
     */
    protected $locator;

    /**
     * @var array
     */
    protected $options;
    
    /**
     * Constructor.
     *
     * @param \Bisna\Service\ServiceLocator $locator
     * @param array $options
     */
    public final function __construct(ServiceLocator $locator, array $options = array())
    {
        $this->locator = $locator;
        $this->options = $options;
        
        $this->initializeService();
    }
    
    /**
     * Extra initialization support for Service.
     * 
     * @return void
     */
    protected function initializeService()
    {
    }

    /**
     * Return Doctrine EntityManager
     *
     * @param string $emName
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getEntityManager($emName = null)
    {
        $dContainer = $this->locator->getDoctrineContainer();

        return $dContainer->getEntityManager($emName);
    }

    /**
     * Retrieves a customized service configuration, allowing to override
     * internal settings. Configuration keys:
     *   - loader
     *   - options
     *
     * @static
     * @return string
     */
    public static function getServiceConfiguration()
    {
        return array();
    }

    /**
     * Returns the ServiceLocator.
     *
     * @return \Bisna\Service\ServiceLocator
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
    
    /**
     * Return a Service option or inner (Nth-level) option.
     * 
     * @param string $optionName 
     * @param mixed $default Optional default value. If ommitted, default is null.
     * @return mixed
     */
    public function getOption($optionName, $default = null)
    {
        $parts = explode('.', $optionName);
        $scope = $this->getOptions();
        
        foreach ($parts as $part) {
            if ( ! (is_array($scope) && isset($scope[$part]))) {
                return $default;
            }
            
            $scope = $scope[$part];
        }
        
        return $scope;
    }
}
