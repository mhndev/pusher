<?php

namespace mhndev\pusher\interfaces;

/**
 * Interface iPusher
 * @package mhndev\digipeyk\services\pusher
 */
interface iPusher
{

    /**
     * @param iMessage $message
     * @param $deviceIdentifier
     * @param array|\Traversable $options
     * @return mixed
     */
    function push(iMessage $message, $deviceIdentifier,  $options = []);


    /**
     * @param iMessage $message
     * @param array|\Traversable $deviceIdentifiers
     * @param array|\Traversable $options
     * @return mixed
     */
    function pushToMany(iMessage $message, $deviceIdentifiers, $options = []);


    /**
     * @param iMessage $message
     * @param $topicName
     * @param array|\Traversable $options
     * @return mixed
     */
    function pushToTopic(iMessage $message, $topicName, $options = []);
}
