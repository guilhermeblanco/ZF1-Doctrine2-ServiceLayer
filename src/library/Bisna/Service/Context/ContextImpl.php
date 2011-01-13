<?php

namespace Bisna\Service\Context;

use Bisna\Service\Exception;

/**
 * ContextImpl class.
 *
 * @author Guilherme Blanco <guilhermeblanco@hotmail.com>
 */
abstract class ContextImpl implements Context
{
    /**
     * @var array Hashmap of binded context entries.
     */
    private $map = array();


    /**
     * {@inheritdoc}
     */
    public function bind($name, $service, array $options = array())
    {
        $originalName = $name;
        $name = mb_strtolower($name);

        if (isset($this->map[$name])) {
            throw new Exception\NameCollisionException("Cannot override Context entry '{$originalName}'.");
        }

        if ($service === null) {
            throw new \InvalidArgumentException("Cannot assign NULL to Context entry '{$originalName}'.");
        }

        $this->map[$name] = array(
            'class'   => $service,
            'config'  => $options
        );
    }

    /**
     * {@inheritdoc}
     */
    public function unbind($name)
    {
        $name = mb_strtolower($name);
        unset($this->map[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function rebind($name, $service, array $options = array())
    {
        $this->unbind($name);
        $this->bind($name, $service, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function rename($oldName, $newName)
    {
        $service = $this->lookup($oldName);
        $this->unbind($oldName);
        $this->bind($newName, $service);
    }

    /**
     * {@inheritdoc}
     */
    public function lookup($name)
    {
        $name = mb_strtolower($name);
        return isset($this->map[$name]) ? $this->map[$name] : null;
    }
}
