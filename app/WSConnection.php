<?php

namespace App;

use App\Interfaces\WSConnectionInterface;
use Ratchet\ConnectionInterface;

/**
 * Class WSConnection
 *
 * @package App
 */
class WSConnection implements WSConnectionInterface
{
    /**
     * Ratchet connection
     *
     * @var ConnectionInterface
     */
    protected $ratchetConn;

    /**
     * @see WSConnectionInterface::signature()
     *
     * @var string
     */
    protected $signature;

    /**
     * WSConnection constructor.
     *
     * @param ConnectionInterface $conn
     */
    public function __construct(ConnectionInterface $conn)
    {
        $this->ratchetConn = $conn;

        $this->sign();
    }

    /** Create connection signature */
    private function sign(): void
    {
        $signaturePayload = $this->ratchetConn->httpRequest->getHeaders();

        unset($signaturePayload['Sec-WebSocket-Key']);

        $this->signature = hash('sha256', serialize($signaturePayload));
    }

    /**
     * @inheritdoc
     *
     * @return int
     */
    public function resourceId(): int
    {
        return $this->ratchetConn->resourceId;
    }

    /**
     * @inheritdoc
     *
     * @return string
     */
    public function signature(): string
    {
        return $this->signature;
    }

    /**
     * @inheritdoc
     *
     * @param string $data
     *
     * @return ConnectionInterface
     */
    public function send($data): ConnectionInterface
    {
        $this->ratchetConn->send($data);

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return mixed
     */
    public function close()
    {
        return $this->ratchetConn->close();
    }
}