<?php

namespace Bisna\Service\Context;

use Bisna\Service\Exception;

/**
 * DirContext class.
 *
 * @author Guilherme Blanco <guilhermeblanco@hotmail.com>
 */
class DirContext extends ContextImpl
{
    /**
     * Constructor.
     *
     * @param array $config Context configuration
     */
    public function __construct(array $config = array())
    {
        $declaredClasses = get_declared_classes();

        $dirIterator  = new \DirectoryIterator($config['dir']);
        $suffix       = (isset($config['suffix']) ? $config['suffix'] : 'Service') . '.php';
        $globalConfig = isset($config['globalConfig']) ? $config['globalConfig'] : array();

        foreach ($dirIterator as $file) {
            if ( ! $file->isDot() && ! $file->isDir() && mb_substr($file->getBasename($suffix), -4) == '.php') {
                require_once $file->getRealPath();

                // Diff declared classes before and after the file require
                $newDeclaredClasses = get_declared_classes();
                $newClassEntries    = array_diff($newDeclaredClasses, $declaredClasses);
                $declaredClasses    = $newDeclaredClasses;

                $name = $file->getFilename($suffix);

                if (count($newClassEntries) !== 1) {
                    $multipleEntries = implode(', ', $newClassEntries);
                    
                    throw new \RuntimeException(
                        "Cannot assign multiple services '{$multipleEntries}' to a single name '{$name}'."
                    );
                }

                $serviceClass  = $newClassEntries[0];
                $serviceConfig = array_merge(
                    $globalConfig, $serviceClass::getServiceConfiguration()
                );
                
                // Do not allow 'class' config entry
                unset($serviceConfig['class']);

                $this->bind($name, $serviceClass, $serviceConfig);
            }
        }
    }
}
