<?php
namespace mhndev\pusher;

use mhndev\pusher\exceptions\UnImplementedPusherHandler;
use mhndev\pusher\interfaces\iPusher;
use mhndev\pusher\pushers\Fcm;
use mhndev\pusher\pushers\Gcm;
use mhndev\pusher\pushers\NginxModule;
use mhndev\pusher\pushers\PubNub;
use mhndev\pusher\pushers\PusherDotCom;

/**
 * Class PusherFactory
 * @package mhndev\pusher
 */
class PusherFactory
{

    const PUSHER_FCM ='Fcm';
    const PUSHER_GCM = 'Gcm';
    const PUSHER_PUBNUB = 'PubNub';
    const PUSHER_PUSHERDOTCOM = 'PusherDotCom';
    const PUSHER_NGINXMODULE = 'NginxModule';

    /**
     * @var array
     */
    protected static $pushers = [
        self::PUSHER_FCM,
        self::PUSHER_GCM,
        self::PUSHER_NGINXMODULE,
        self::PUSHER_PUBNUB,
        self::PUSHER_PUSHERDOTCOM
    ];

    /**
     * @param $name
     * @param array | \Traversable $options
     * @return iPusher
     *
     * @throws UnImplementedPusherHandler
     */
    public static function createPusher($name, $options)
    {
        if(! in_array($name, self::$pushers)){
            throw new UnImplementedPusherHandler();
        }

        $methodName = 'create'.$name;

        return self::$methodName($options);
    }



    /**
     * @param array|\Traversable $options
     *
     * @return iPusher
     */
    protected static function createFcm($options)
    {
        return new Fcm($options['apiKey'], $options['httpClient']);
    }


    /**
     * @param array|\Traversable $options
     *
     * @return iPusher
     */
    protected static function createGcm($options)
    {
        return new Gcm($options['apiKey'], $options['retry_attempt']);
    }


    /**
     * @param array|\Traversable $options
     *
     * @return iPusher
     */
    protected static function createPubNub($options)
    {
        return new PubNub($options['public_key'], $options['subscribe_key'], $options['secret_key']);
    }


    /**
     * @param array|\Traversable $options
     *
     * @return iPusher
     */
    protected static function createNginxModule($options)
    {
        return new NginxModule($options['httpClient'], $options['endpoint']);
    }


    /**
     * @param array|\Traversable $options
     *
     * @return iPusher
     */
    protected static function createPusherDotCom($options)
    {
        return new PusherDotCom(
            $options['app_key'],
            $options['app_secret'],
            $options['app_id'],
            $options['options']
        );

    }
}
