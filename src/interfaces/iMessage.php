<?php

namespace mhndev\pusher\interfaces;

/**
 * Interface iMessage
 * @package mhndev\pusher\interfaces
 */
interface iMessage
{
    /**
     * @return string
     */
    function getNamespace();

    /**
     * @return string
     */
    function getData();

    /**
     * @return array|\Traversable|null
     */
    function getOptions();


    /**
     * @return string
     */
    function __toString();
}
