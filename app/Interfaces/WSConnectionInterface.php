<?php

namespace App\Interfaces;

use Ratchet\ConnectionInterface as RatchetConnection;

/**
 * WebSocket connection contract
 *
 * Interface WSConnectionInterface
 *
 * @package App\Interfaces
 */
interface WSConnectionInterface extends RatchetConnection
{
    /**
     * Returns the resource identifier for this connection
     *
     * @return int
     */
    public function resourceId(): int;

    /**
     * Get a unique connection identifier,
     * such as a signature,
     * based on information from headers
     * of the WebSocket connection.
     *
     * @return string
     */
    public function signature(): string;
}