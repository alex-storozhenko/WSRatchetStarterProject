<?php

namespace App\MessageHandlers;

use App\Interfaces\WSMessageHandlerInterface;
use BadMethodCallException;

/**
 * Class MessageHandler.
 */
abstract class MessageHandler implements WSMessageHandlerInterface
{
    /**
     * {@inheritdoc}
     *
     * @param string $action
     * @param array  $args
     *
     * @return mixed
     */
    public function handle(string $action, array $args)
    {
        return $this->{$action}(...$args);
    }

    /**
     * {@inheritdoc}
     *
     * Denied access to magic __call
     *
     * @param $name
     * @param $arguments
     */
    public function __call($name, $arguments)
    {
        throw new BadMethodCallException("Method [{$name}] does not exist on [".get_class($this).'].');
    }
}
