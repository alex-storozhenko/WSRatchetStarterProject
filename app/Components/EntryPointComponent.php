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
 * Class EntryPointComponent
 *
 * @package App\Components
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
     * @param WSLoggerInterface $logger
     */
    public function __construct(WSConnectionRepositoryInterface $repository, WSLoggerInterface $logger)
    {
        $this->repository = $repository;
        $this->logger = $logger;
    }

    /**
     * @inheritdoc
     *
     * @param ConnectionInterface $conn
     */
    function onOpen(ConnectionInterface $conn)
    {
        $conn = (new WSConnection($conn));

        $this->repository->put($conn);
        $this->logger->happenedWithConnection('Connection opened.', $conn);
    }

    /**
     * @inheritdoc
     *
     * @param ConnectionInterface $conn
     * @param MessageInterface $msg
     */
    public function onMessage(ConnectionInterface $conn, MessageInterface $msg)
    {
        $conn = $this->repository->get($conn->resourceId);

        $this->logger->happenedWithConnection("Receive \"{$msg}\".", $conn);

        $conn->send($msg);
    }

    /**
     * @inheritdoc
     *
     * @param ConnectionInterface $conn
     */
    public function onClose(ConnectionInterface $conn)
    {
        $conn = $this->repository->get($conn->resourceId);

        $this->repository->delete($conn->resourceId());
        $this->logger->happenedWithConnection('Connection closed.', $conn);
    }

    /**
     * @inheritdoc
     *
     * @param ConnectionInterface $conn
     * @param Exception $e
     */
    public function onError(ConnectionInterface $conn, Exception $e)
    {
        $this->logger->exception($e);
    }
}