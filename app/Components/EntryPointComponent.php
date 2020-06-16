<?php

namespace App\Components;

use App\Interfaces\WSConnectionRepositoryInterface;
use App\Interfaces\WSLoggerInterface;
use App\WSConnection;
use Exception;
use Ratchet\ConnectionInterface;
use Ratchet\RFC6455\Messaging\MessageInterface;
use Ratchet\WebSocket\MessageComponentInterface;

/**
 * Entry point for WebSocket clients
 * Class EntryPointComponent.
 */
class EntryPointComponent implements MessageComponentInterface
{
    /** @var WSConnectionRepositoryInterface */
    protected $repository;

    /** @var WSLoggerInterface */
    protected $logger;

    /**
     * EntryPointComponent constructor.
     *
     * @param WSConnectionRepositoryInterface $repository
     * @param WSLoggerInterface               $logger
     */
    public function __construct(WSConnectionRepositoryInterface $repository, WSLoggerInterface $logger)
    {
        $this->repository = $repository;
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     *
     * @param ConnectionInterface $connection
     */
    public function onOpen(ConnectionInterface $connection): void
    {
        $connection = new WSConnection($connection);

        $this->repository->put($connection);
        $this->logger->happenedWithConnection('Connection opened.', $connection);
    }

    /**
     * {@inheritdoc}
     *
     * @param ConnectionInterface $connection
     * @param MessageInterface    $msg
     */
    public function onMessage(ConnectionInterface $connection, MessageInterface $msg): void
    {
        $connection = $this->repository->get($connection->resourceId);

        $this->logger->happenedWithConnection("Receive \"{$msg}\".", $connection);

        $connection->send($msg);
    }

    /**
     * {@inheritdoc}
     *
     * @param ConnectionInterface $connection
     */
    public function onClose(ConnectionInterface $connection): void
    {
        $connection = $this->repository->get($connection->resourceId);

        $this->repository->delete($connection->resourceId());
        $this->logger->happenedWithConnection('Connection closed.', $connection);
    }

    /**
     * {@inheritdoc}
     *
     * @param ConnectionInterface $connection
     * @param Exception           $e
     */
    public function onError(ConnectionInterface $connection, Exception $e): void
    {
        $this->logger->exception($e);
    }
}
