<?php

namespace App\Interfaces;

/**
 * Repository of WebSockets connections contract.
 *
 * Interface WSConnectionRepositoryInterface
 */
interface WSConnectionRepositoryInterface
{
    /***
     * Store this connection
     *
     * @param WSConnectionInterface $connection
     *
     * @return $this
     */
    public function put(WSConnectionInterface $connection): self;

    /**
     * Delete this connection.
     *
     * @param int $connectionId
     *
     * @return $this
     */
    public function delete(int $connectionId): self;

    /**
     * Get connection by id.
     *
     * @param int $connectionId
     *
     * @return null|WSConnectionInterface
     */
    public function get(int $connectionId): ?WSConnectionInterface;

    /**
     * Returns true if the given connection is exist in repository,
     * false otherwise.
     *
     * @param int $connectionId
     *
     * @return bool
     */
    public function inRepository(int $connectionId): bool;

    /**
     * Returns all connections.
     *
     * @return WSConnectionInterface[]
     */
    public function all(): array;

    /**
     * Returns connections, that
     * suitable for these conditions.
     *
     * @param $property
     * @param $value
     * @param $operator
     *
     * @return WSConnectionInterface[]
     */
    public function where($property, $value, string $operator = '='): array;
}
