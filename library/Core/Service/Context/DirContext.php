<?php

namespace Core\Service\Context;

use Core\Service\Exception;

/**
 * DirContext class.
 *
 * @author Guilherme Blanco <guilhermeblanco@hotmail.com>
 */
class DirContext extends ContextImpl
{
    /**
     * @var array Hashmap of binded context entries.
     */
    private $map = array();

    
    /**
     * Constructor.
     *
     * @param array $config Context configuration
     */
    public function __construct(array $config = array())
    {
        $declaredClasses = get_declared_classes();

        $dirIterator = new \DirectoryIterator($config['dir']);
        $suffix = (isset($config['suffix']) ? $config['suffix'] : 'Service') . '.php';
        $globalOptions = isset($config['serviceOptions']) ? $config['serviceOptions'] : array();

        foreach ($dirIterator as $file) {
            if ( ! $file->isDot() && ! $file->isDir() && mb_substr($file->getBasename($suffix), -4) == '.php') {
                require_once $file->getRealPath();

                $newDeclaredClasses = get_declared_classes();
                $newEntries = array_diff($newDeclaredClasses, $declaredClasses);
                $declaredClasses = $newDeclaredClasses;

                $name = $file->getFilename($suffix);

                if (count($newEntries) != 1) {
                    throw new \RuntimeException(
                        "Cannot assign multiple services '" . implode(', ', $newEntries) . "' to a single name '{$name}'."
                    );
                }

                $this->bind($name, $newEntries[0], $globalOptions);
            }
        }
    }
}