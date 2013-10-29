<?php

namespace Bisna\Service\Context;

use Bisna\Service\Exception;

/**
 * DirContext class.
 *
 * @author Guilherme Blanco <guilhermeblanco@hotmail.com>
 * @author LF Bittencourt <lf@lfbittencourt.com>
 */
class DirContext extends ContextImpl
{
    /**
     * Constructor.
     *
     * @param array $config Context configuration
     */
    public function __construct($path, array $config = array())
    {
        if (is_dir($path)) {
            $declaredClasses = get_declared_classes();
            $dirIterator     = new \DirectoryIterator($path);
            $suffix          = (isset($config['suffix']) ? $config['suffix'] : 'Service') . '.php';

            foreach ($dirIterator as $file) {
                if ( ! $file->isDir() && preg_match('/' . preg_quote($suffix) . '$/', $file->getFilename()) === 1 ) {
                    require_once $file->getRealPath();

                    // Diff declared classes before and after the file require
                    $newDeclaredClasses = get_declared_classes();
                    $newClassEntries    = array_diff($newDeclaredClasses, $declaredClasses);

                    if (count($newClassEntries) > 0) {
                        $name = $file->getBasename($suffix);
                        $serviceClass  = array_pop($newClassEntries);
                        $serviceConfig = array();

                        if (isset($config[mb_strtolower($name)])) {
                            $serviceConfig['options'] = $config[mb_strtolower($name)];
                        }

                        $this->bind($name, $serviceClass, $serviceConfig);
                    }
                }
            }
        }
    }
}