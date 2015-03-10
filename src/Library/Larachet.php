<?php namespace Admsa\Larachet\Library;

use ZMQContext, ZMQ;

class Larachet {

    /**
     * Zmq context
     *
     * @var ZMQContext
     */
    protected $context;

    /**
     * Larachet constructor
     */
    public function __construct(ZMQContext $context)
    {
        $this->context = $context;
    }

    /**
     * Push data to the user.
     *
     * @param string $eventName
     * @param array $data
     *
     * @return Socket
     */
    public function push($eventName, array $data)
    {
        $data = json_encode(['event' => $eventName, 'data' => $data]);
        return $this->getSocket()->send($data);
    }

    /**
     * Get socket
     *
     * @return Socket
     */
    protected function getSocket()
    {
        $socket = $this->context->getSocket(ZMQ::SOCKET_PUSH, 'Larachet Notification');
        $socket->connect("tcp://".PushServer::HOST_IP.":".PushServer::HOST_PORT);

        return $socket;
    }
}
