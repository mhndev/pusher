<?php
namespace mhndev\pusher;

use mhndev\pusher\interfaces\iMessage;
use mhndev\pusher\interfaces\iPusher;
use Pusher;

/**
 * Class PusherDotCom
 * @package mhndev\pusher
 */
class PusherDotCom implements iPusher
{

    /**
     * @var Pusher
     */
    protected $pusherClient;

    /**
     * PusherDotCom constructor.
     *
     * @param $app_key
     * @param $app_secret
     * @param $app_id
     * @param array $options
     */
    public function __construct($app_key, $app_secret, $app_id, array $options = [])
    {
        $this->pusherClient = new Pusher($app_key, $app_secret, $app_id, $options );
    }



    /**
     * @param iMessage $message
     * @param $deviceIdentifier
     * @param array $options
     * @return mixed
     */
    function push(iMessage $message, $deviceIdentifier, $options = [])
    {
        if(empty($options['event'])){
            throw new \InvalidArgumentException(printf('event option required in push method in PusherDotCom driver.'));
        }

        $socketId = empty($options['socket_id']) ? null : $options['socket_id'];
        $debug = empty($options['debug']) ? false : $options['debug'];
        $already_encoded = empty($options['already_encoded']) ? null : $options['already_encoded'];


        $response = $this->pusherClient->trigger(
            $deviceIdentifier,
            $options['event'],
            $message,
            $socketId,
            $debug,
            $already_encoded
        );


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
        if(empty($options['event'])){
            throw new \InvalidArgumentException(printf('event option required in push method in PusherDotCom driver.'));
        }

        $socketId = empty($options['socket_id']) ? null : $options['socket_id'];
        $debug = empty($options['debug']) ? false : $options['debug'];
        $already_encoded = empty($options['already_encoded']) ? null : $options['already_encoded'];


        $response = $this->pusherClient->trigger(
            $deviceIdentifiers,
            $options['event'],
            $message,
            $socketId,
            $debug,
            $already_encoded
        );


        return $response;
    }

    /**
     * @param iMessage $message
     * @param $topicName
     * @param array $options
     * @return mixed
     */
    function pushToTopic(iMessage $message, $topicName, $options = [])
    {
        if(empty($options['event'])){
            throw new \InvalidArgumentException(printf('event option required in push method in PusherDotCom driver.'));
        }

        $socketId = empty($options['socket_id']) ? null : $options['socket_id'];
        $debug = empty($options['debug']) ? false : $options['debug'];
        $already_encoded = empty($options['already_encoded']) ? null : $options['already_encoded'];


        $response = $this->pusherClient->trigger(
            $topicName,
            $options['event'],
            $message,
            $socketId,
            $debug,
            $already_encoded
        );


        return $response;
    }
}
