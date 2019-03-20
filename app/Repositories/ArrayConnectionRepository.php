<?php

namespace App\Repositories;

use App\Interfaces\WSConnectionInterface;
use App\Interfaces\WSConnectionRepositoryInterface;
use InvalidArgumentException;

/**
 * Class ArrayConnectionRepository
 *
 * @package App\Repositories
 */
class ArrayConnectionRepository implements WSConnectionRepositoryInterface
{
    /**
     * Storage
     *
     * Format:
     * Key is integer $ratchetConn->resourceId
     * and value it's Connection instance
     *
     * @example:
     * <code>
     * [
     *      (int)$resourceId => (App\Interfaces\WSConnectionInterface)Connection,
     *      (int)$resourceId => (App\Interfaces\WSConnectionInterface)Connection,
     *      (int)$resourceId => (App\Interfaces\WSConnectionInterface)Connection
     * ]
     * </code>
     * @var array
     */
    protected static $connections = [];

    /**
     * @inheritdoc
     *
     * @param int $connectionId
     *
     * @return WSConnectionInterface
     */
    public function get(int $connectionId): ?WSConnectionInterface
    {
        return static::$connections[$connectionId] ?? null;
    }

    /**
     * @inheritdoc
     *
     * @param int $connectionId
     *
     * @return bool
     */
    public function delete(int $connectionId): WSConnectionRepositoryInterface
    {
        unset(static::$connections[$connectionId]);

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return array
     */
    public function all(): array
    {
        return static::$connections;
    }

    /**
     * @inheritdoc
     *
     * @param WSConnectionInterface $connection
     *
     * @return WSConnectionRepositoryInterface
     */
    public function put(WSConnectionInterface $connection): WSConnectionRepositoryInterface
    {
        static::$connections[$connection->resourceId()] = $connection;

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @param $connectionId
     *
     * @return bool
     */
    public function inRepository(int $connectionId): bool
    {
        return array_key_exists($connectionId, static::$connections);
    }

    /**
     * @inheritdoc
     *
     * Acceptable properties: resourceId, signature
     *
     * @param string $property
     *
     * For selection by id value must have possibility casted to integer
     * For selection by signature value must be string @see Connection::getSignature
     * @param $value
     *
     * For selection by resourceId value  - Acceptable operators: [=, !=, <, >, <=, >=]
     * For selection by signature - Acceptable operators: [=, !=]
     * @param string $operator
     *
     * @return WSConnectionRepositoryInterface[]
     */
    public function where($property, $value, string $operator = '='): array
    {
        switch ($property) {
            case 'resourceId':
                $value = (int)$value;
                $connections = $this->whereResourceId($value, $operator);

                break;
            case 'signature':
                $value = (string)$value;
                $connections = $this->whereSignature($value, $operator);

                break;
            default:
                throw new InvalidArgumentException('Passed invalid property of Connection. Acceptable properties: id, owner, ownerType, app, signature');
        }

        return $connections;
    }

    /**
     * Selection connections by id
     *
     * @param int $value
     *
     * Acceptable operators: @see ArrayConnectionRepository::where
     * @param string $operator
     *
     * @return WSConnectionInterface[]
     */
    public function whereResourceId(int $value, string $operator = '='): array
    {
        switch ($operator) {
            case '=':
                return array_filter(static::$connections, function (int $id) use ($value) {
                    return $value === $id;
                }, ARRAY_FILTER_USE_KEY);

                break;
            case '!=':
                return array_filter(static::$connections, function (int $id) use ($value) {
                    return $value !== $id;
                }, ARRAY_FILTER_USE_KEY);

                break;
            case '<':
                return array_filter(static::$connections, function (int $id) use ($value) {
                    return $value < $id;
                }, ARRAY_FILTER_USE_KEY);

                break;
            case '>':
                return array_filter(static::$connections, function (int $id) use ($value) {
                    return $value > $id;
                }, ARRAY_FILTER_USE_KEY);

                break;
            case '<=':
                return array_filter(static::$connections, function (int $id) use ($value) {
                    return $value <= $id;
                }, ARRAY_FILTER_USE_KEY);

                break;
            case '>=':
                return array_filter(static::$connections, function (int $id) use ($value) {
                    return $value >= $id;
                }, ARRAY_FILTER_USE_KEY);

                break;
            default:
                throw new InvalidArgumentException("Passed invalid operator for id comparing. Acceptable operators: =, !=, <, >, <=, >=");
        }
    }

    /**
     * Selection connections by connection signature
     *
     * @param string $value
     *
     * Acceptable operators: @see ArrayConnectionRepository::where
     * @param string $operator
     *
     * @return WSConnectionInterface[]
     */
    public function whereSignature(string $value, string $operator): ?array
    {
        switch ($operator) {
            case '=':
                return array_filter(static::$connections, function (WSConnectionInterface $c) use ($value) {
                    return $value === $c->signature();
                });

                break;
            case '!=':
                return array_filter(static::$connections, function (WSConnectionInterface $c) use ($value) {
                    return $value !== $c->signature();
                });

                break;
            default:
                throw new InvalidArgumentException("Passed invalid operator for signature comparing. Acceptable operators: =, !=");
        }
    }
}