<?php

namespace Bisna\Service\Context;

/**
 * Context interface.
 *
 * @author Guilherme Blanco <guilhermeblanco@hotmail.com>
 */
interface Context
{
    /**
     * Binds the specified name to the specified service in this context. The
     * specified name may not be null. The specified service may not be empty,
     * otherwise an InvalidArgumentException is thrown.
     *
     * @param string $name
     * @param string $service
     */
    public function bind($name, $service, array $options = array());

    /**
     * Removes the terminal atomic name component of the specified name from the
     * bindings in this context. The operation succeeds whether or not the
     * terminal atomic name exists.
     *
     * @param string $name
     */
    public function unbind($name);

    /**
     * Binds the specified name to the specified service, replacing any existing
     * binding for the specified name. The specified name may not be empty. The
     * specified service may be null.
     *
     * @param string $name
     * @param string $service
     */
    public function rebind($name, $service, array $options = array());

    /**
     * Binds a specified new name to the service previously bound to the
     * specified old name. The old name is removed from the bindings for this
     * context. Neither the new nor the old name may be empty.
     *
     * @param string $oldName
     * @param string $newName
     */
    public function rename($oldName, $newName);

    /**
     * Returns the object bound to the specified name in this context.
     *
     * @param string $name
     */
    public function lookup($name);
}