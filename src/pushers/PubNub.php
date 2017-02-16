<?php
namespace mhndev\pusher\pushers;

use mhndev\pusher\interfaces\iMessage;
use mhndev\pusher\interfaces\iPusher;

/**
 * Class pubNub
 * @package mhndev\pusher
 */
class PubNub implements iPusher
{

    /**
     * @var \Pubnub\Pubnub
     */
    protected $pubNubClient;


    /**
     * PusherPubnub constructor.
     *
     * @param $subscribe_key
     * @param $publish_key
     * @param $secret_key
     */
    public function __construct($publish_key, $subscribe_key, $secret_key)
    {
        $this->pubNubClient = new Pubnub($publish_key, $subscribe_key, $secret_key);
    }


    /**
     * @param iMessage $message
     * @param $deviceIdentifier
     * @param array $options
     * @return mixed
     */
    function push(iMessage $message, $deviceIdentifier, $options = [])
    {
        if(empty($options['channel'])){
            throw new \InvalidArgumentException(printf(
                'channel option required in push method in Pubnub driver.'
            ));
        }

        $response = $this->pubNubClient->publish($deviceIdentifier, $message);

        return $response;
    }

    /**
     * @param iMessage $message
     * @param array|\Traversable $deviceIdentifiers
     * @param array $options
     * @return mixed
     */
    function pushToMany(iMessage $message, $deviceIdentifiers, $options = [])
    {
        // TODO: Implement pushToMany() method.
    }

    /**
     * @param iMessage $message
     * @param $topicName
     * @param array $options
     * @return mixed
     */
    function pushToTopic(iMessage $message, $topicName, $options = [])
    {
        // TODO: Implement pushToTopic() method.
    }
}
