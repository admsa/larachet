<?php namespace Admsa\Larachet\Library;

use Ratchet\ConnectionInterface;
use Ratchet\Wamp\WampServerInterface;

/**
 * Pusher class that handles event.
 *
 * Author: http://socketo.me/docs/push
 */
class Pusher implements WampServerInterface {
    /**
     * A lookup of all the topics clients have subscribed to
     */
    protected $subscribedTopics = array();

    public function onSubscribe(ConnectionInterface $conn, $topic)
    {
        $this->subscribedTopics[$topic->getId()] = $topic;
    }

    /**
     * @param string JSON'ified string we'll receive from ZeroMQ
     */
    public function onFireEvent($entry)
    {
        $entryData = json_decode($entry, true);

        // If the lookup topic isn't set there is no one to publish to
        if ( ! array_key_exists($entryData['event'], $this->subscribedTopics)) {
            return;
        }

        $topic = $this->subscribedTopics[$entryData['event']];

        // re-send the data to all the clients subscribed to that category
        $topic->broadcast($entryData['data']);
    }

    public function onUnSubscribe(ConnectionInterface $conn, $topic)
    {
        // Not implemented
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // Not implemented
    }

    public function onClose(ConnectionInterface $conn)
    {
        // Not implemented
    }

    public function onCall(ConnectionInterface $conn, $id, $topic, array $params)
    {
        // In this application if clients send data it's because the user hacked around in console
        $conn->callError($id, $topic, 'You are not allowed to make calls')->close();
    }

    public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible)
    {
        // In this application if clients send data it's because the user hacked around in console
        $conn->close();
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        // Not implemented
    }
}
