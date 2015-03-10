<?php namespace Admsa\Larachet\Library;

use React\EventLoop\Factory as ReactFactory;
use React\EventLoop\StreamSelectLoop;
use React\ZMQ\Context;
use React\Socket\Server;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\Wamp\WampServer;
use ZMQ;

/**
 * PushServer class userd to run ratchet server.
 *
 * Modified From: http://socketo.me/docs/push
 */

class PushServer {
    /**
     * Holds pusher instance
     *
     * @var Libraries\Ratchet\Pusher
     */
    protected $pusher;

    /**
     * Binding to 127.0.0.1 means only the
     * client can connect to itself.
     *
     * @var string
     */
    const HOST_IP = '127.0.0.1';

    /**
     * Bind to this port
     *
     * @var string
     */
    const HOST_PORT = '5555';

    /**
     * Web server port.
     *
     * @var int
     */
    const SERVER_PORT = 8080;

    /**
     * Event name
     *
     * @var string
     */
    protected $eventName = 'onFireEvent';

    /**
     * PushServer constructor
     */
    public function __construct(Pusher $pusher)
    {
        $this->pusher = $pusher;
    }

    /**
     * Run ratchet server.
     *
     * @return void
     */
    public function run() {
        $loop = ReactFactory::create();

        // Listen for the web server to make a ZeroMQ push after an ajax request
        $this->listen($loop);

        // Set up our WebSocket server for clients wanting real-time updates
        $webServer = $this->setUpWebSocket($loop);

        $loop->run();
    }

    /**
     * Set up our WebSocket server for clients wanting real-time updates
     *
     * @param StreamSelectLoop $loop
     * @return Ratchet\Server\IoServer
     */
    protected function setUpWebSocket(StreamSelectLoop $loop)
    {
        $webSock = new Server($loop);

        // Binding to 0.0.0.0 means remotes can connect
        $webSock->listen(static::SERVER_PORT, '0.0.0.0');

        return new IoServer(
            new HttpServer(
                new WsServer(
                    new WampServer(
                        $this->pusher
                    )
                )
            ),
            $webSock
        );
    }

    /**
     * Listen for the web server to make a ZeroMQ
     * push after an ajax request.
     *
     * @param StreamSelectLoop $loop
     * @return React\ZMQ\SocketWrapper
     */
    protected function listen(StreamSelectLoop $loop)
    {
        $context = new Context($loop);
        return $this->pullSocket($context);
    }

    /**
     * Get socket wrapper.
     *
     * @param Context $context
     * @return React\ZMQ\SocketWrapper
     */
    protected function pullSocket(Context $context)
    {
        $pull = $context->getSocket(ZMQ::SOCKET_PULL);

        $pull->bind("tcp://".static::HOST_IP.":".static::HOST_PORT);
        $pull->on('message', array($this->pusher, $this->eventName));

        return $pull;
    }
}
