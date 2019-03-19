<?php

namespace App\Loggers;

use App\Interfaces\WSConnectionInterface;
use App\Interfaces\WSLoggerInterface;
use Exception;

/**
 * Class StdOutLog
 *
 * @package App\Loggers
 */
class StdOutLogger implements WSLoggerInterface
{
    /**
     * @inheritdoc
     *
     * @param string $whatHappened
     * @param WSConnectionInterface $connection
     * @param string $method
     */
    public function happenedWithConnection(string $whatHappened, WSConnectionInterface $connection, string $method = ''):void
    {
        // Divider
        $this->write('');

        if ($method) {
            $this->write($method);
        }

        $this->connectionInfo($connection);
        $this->write('Action: ' . $whatHappened);
    }

    /**
     * @inheritdoc
     *
     * @param string $s
     */
    public function write(string $s):void
    {
        echo "{$s} ". PHP_EOL;
    }

    /**
     * @inheritdoc
     *
     * @param WSConnectionInterface $connection
     *
     * @return mixed|void
     */
    public function connectionInfo(WSConnectionInterface $connection)
    {
        $id  = $connection->resourceId();
        $s   = $connection->signature();

        $this->write('Connection:');
        $this->write("Connection[id] - {$id}");
        $this->write("Connection[signature] - {$s}");
    }

    /**
     * @inheritdoc
     *
     * @param Exception $e
     */
    public function exception(Exception $e):void
    {
        $t = get_class($e);
        $m = $e->getMessage();
        $f = $e->getFile();
        $l = $e->getLine();

        $this->write("Exception - {$t}: {$m} in file: {$f} on line: {$l}");
    }
}