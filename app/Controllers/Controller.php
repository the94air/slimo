<?php

namespace App\Controllers;

use Interop\Container\ContainerInterface;

abstract class Controller
{
    /**
     * The container instance.
     *
     * @var \Interop\Container\ContainerInterface
     */
    protected $c;

    /**
     * Set up controllers to have access to the container.
     *
     * @param \Interop\Container\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->c = $container;
    }

    /**
     * For hiding the controller container.
     *
     * @param \App\Controllers\Controller $property
     */
    public function __get($property)
    {
        if ($this->c->{$property}) {
            return $this->c->{$property};
        }
    }
}
