<?php

namespace App\Interfaces;

/**
 * Interface WSMessageHandler.
 */
interface WSMessageHandlerInterface
{
    /**
     * Handle.
     *
     * @param string $action
     * @param array  $args
     *
     * @return mixed
     */
    public function handle(string $action, array $args);
}
