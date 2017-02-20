<?php
namespace mhndev\pusher;

use mhndev\pusher\exceptions\InvalidArgumentException;
use mhndev\pusher\exceptions\PushFailException;
use mhndev\pusher\interfaces\iMessage;
use mhndev\pusher\interfaces\iPusher;
use PHP_GCM\InvalidRequestException;
use PHP_GCM\Message;
use PHP_GCM\Notification;
use PHP_GCM\Sender;

/**
 * Class Gcm
 * @package mhndev\pusher
 */
class Gcm implements iPusher
{

    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var Sender
     */
    protected $gcmClient;


    /**
     * @var int
     */
    protected $retry_attempt = 1;


    /**
     * PusherGcm constructor.
     *
     * @param $apiKey
     * @param int $retry_attempt
     *
     * @throws InvalidArgumentException
     */
    public function __construct($apiKey, $retry_attempt = 1)
    {
        if(empty($apiKey)){
            throw new InvalidArgumentException('API key parameter is needed.');
        }

        $this->retry_attempt = $retry_attempt;

        $this->apiKey = $apiKey;
        $this->gcmClient = new Sender($this->apiKey);
    }


    /**
     * @param iMessage $message
     * @param $deviceIdentifier
     * @param array $options
     *
     * @return mixed
     *
     * @throws PushFailException
     */
    function push(iMessage $message, $deviceIdentifier, $options = [])
    {
        $gcmMessage = $this->generateGcmMessage($message, $options);

        try{
            $result = $this->gcmClient
                ->retries($this->retry_attempt)
                ->send($gcmMessage, $deviceIdentifier);
        }

        catch (InvalidRequestException $e){
            throw new PushFailException();
        }


        return $result;
    }

    /**
     * @param iMessage $message
     * @param array|\Traversable $deviceIdentifiers
     * @param array $options
     *
     * @return mixed
     *
     * @throws InvalidArgumentException
     * @throws PushFailException
     */
    function pushToMany(iMessage $message, $deviceIdentifiers, $options = [])
    {
        $gcmMessage = $this->generateGcmMessage($message, $options);

        if( !is_array($deviceIdentifiers) || !$deviceIdentifiers instanceof $deviceIdentifiers){
            throw new InvalidArgumentException(
                sprintf(
                    'device identifiers should be array or instance of %s', \Traversable::class
                )
            );
        }


        try{
            $result = $this->gcmClient
                ->retries($this->retry_attempt)
                ->send($gcmMessage, $deviceIdentifiers);
        }

        catch (InvalidRequestException $e){
            throw new PushFailException();
        }


        return $result;
    }


    /**
     * @param iMessage $message
     * @param $topicName
     * @param array $options
     *
     * @return mixed
     * @throws \Exception
     */
    function pushToTopic(iMessage $message, $topicName, $options = [])
    {
        throw new \Exception('already does not support');
    }



    /**
     * @param iMessage $message
     * @param array | \Traversable $options
     *
     * @return Message
     */
    protected function generateGcmMessage(iMessage $message, $options)
    {
        $gcmMessage = (new Message());

        if(!empty($options['collapseKey'])){
            $gcmMessage->collapseKey($options['collapseKey']);
        }

        if(!empty($options['dryRun'])){
            $gcmMessage->dryRun($options['dryRun']);
        }

        if(!empty($options['priority'])){
            $gcmMessage->priority($options['priority']);
        }

        if(!empty($options['timeToLive'])){
            $gcmMessage->timeToLive($options['timeToLive']);
        }

        if(!empty($options['content_available'] )){
            $gcmMessage->contentAvailable($options['content_available']);
        }

        if(!empty($options['notification'])){

            $notification = new Notification();

            if(!empty($options['notification']['title'])){
                $notification->title($options['notification']['title']);
            }

            if(!empty($options['notification']['body'])){
                $notification->body($options['notification']['body']);
            }

            if(!empty($options['notification']['icon'])){
                $notification->icon($options['notification']['icon']);
            }

            if(!empty($options['notification']['color'])){
                $notification->color($options['notification']['color']);
            }

            if(!empty($options['notification']['sound'])){
                $notification->sound($options['notification']['sound']);
            }


            if(!empty($options['notification']['tag'])){
                $notification->tag($options['notification']['tag']);
            }

            if(!empty($options['notification']['badge'])){
                $notification->badge($options['notification']['badge']);
            }

            if(!empty($options['notification']['clickAction'])){
                $notification->clickAction($options['notification']['clickAction']);
            }

            if(!empty($options['notification']['bodyLocArgs'])){
                $notification->bodyLocArgs($options['notification']['bodyLocArgs']);
            }

            $gcmMessage->notification($notification);
        }



        $gcmMessage->data($message->__toString());


        return $gcmMessage;
    }

}
