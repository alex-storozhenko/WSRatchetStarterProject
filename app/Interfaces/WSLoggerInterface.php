<?php

namespace App\Interfaces;

use Exception;

/**
 * WebSocket logger object contract
 *
 * Interface WSLoggerInterface
 *
 * @package App\Interfaces
 */
interface WSLoggerInterface
{
    /**
     * Log action,
     * that happen with connection
     *
     * @param string $whatHappened
     * @param WSConnectionInterface $connection
     *
     * @return void
     */
    public function happenedWithConnection(string $whatHappened, WSConnectionInterface $connection):void;

    /**
     * Log information about connection
     *
     * @param WSConnectionInterface $connection
     *
     * @return mixed
     */
    public function connectionInfo(WSConnectionInterface $connection);

    /**
     * Log exception
     *
     * @param Exception $e
     *
     * @return void
     */
    public function exception(Exception $e):void;

    /**
     * Write into log
     *
     * @param string $s
     *
     * @return void
     */
    public function write(string $s):void;
}