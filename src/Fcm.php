<?php
namespace mhndev\pusher;

use GuzzleHttp\ClientInterface;
use mhndev\pusher\exceptions\PushFailException;
use mhndev\pusher\interfaces\iMessage;
use mhndev\pusher\interfaces\iPusher;
use paragraph1\phpFCM\Client;
use paragraph1\phpFCM\Message;
use paragraph1\phpFCM\Notification;
use paragraph1\phpFCM\Recipient\Device;
use paragraph1\phpFCM\Recipient\Topic;

/**
 * Class Fcm
 * @package mhndev\pusher
 */
class Fcm implements iPusher
{

    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var mixed
     */
    protected $fcmClient;


    const DEVICE = 'device';

    const TOPIC  = 'topic';

    /**
     * Fcm constructor.
     * @param $apiKey
     * @param $httpClient
     * @throws \Exception
     */
    function __construct($apiKey, $httpClient)
    {
        $this->apiKey = $apiKey;
        $this->fcmClient = new Client();

        if(! $httpClient instanceof ClientInterface){
            throw new \Exception('specified http client currently just support guzzle http client.');
        }

        $this->fcmClient->setApiKey($this->apiKey);
        $this->fcmClient->injectHttpClient($httpClient);
    }

    /**
     * @param iMessage $message
     * @param $deviceIdentifier
     * @param array|\Traversable $options
     * @return mixed
     * @throws PushFailException
     */
    function push(iMessage $message, $deviceIdentifier, $options = [])
    {
        $message = $this->createMessage($message, $options);

        $message->addRecipient(new Device($deviceIdentifier));

        $response = $this->fcmClient->send($message);

        if($response->getStatusCode() == 200){
            return $response->getBody()->getContents();
        }
        else{
            throw new PushFailException('Push Failed Exception.');
        }

    }


    /**
     * @param iMessage $message
     * @param array|\Traversable $deviceIdentifiers
     * @param array|\Traversable $options
     * @return mixed
     * @throws PushFailException
     */
    function pushToMany(iMessage $message, $deviceIdentifiers, $options = [])
    {
        $message = $this->createMessage($message, $options);

        foreach ($deviceIdentifiers as $deviceIdentifier){
            $message->addRecipient(new Device($deviceIdentifier));
        }

        $response = $this->fcmClient->send($message);

        if($response->getStatusCode() == 200){
            return $response->getBody()->getContents();
        }
        else{
            throw new PushFailException('Push Failed Exception.');
        }
    }

    /**
     * @param iMessage $message
     * @param $topicName
     * @param array|\Traversable $options
     * @return mixed
     * @throws PushFailException
     */
    function pushToTopic(iMessage $message, $topicName, $options = [])
    {
        $message = $this->createMessage($message, $options);

        $message->addRecipient(new Topic($topicName));

        $response = $this->fcmClient->send($message);

        if($response->getStatusCode() == 200){
            return $response->getBody()->getContents();
        }
        else{
            throw new PushFailException('Push Failed Exception.');
        }
    }



    /**
     * @param iMessage $message
     * @param array $options
     * @return iMessage|Message
     */
    protected function createMessage(iMessage $message, $options = [])
    {
        $note = new Notification($options['title'], $message);

        empty($options['icon'])        ? : $note->setIcon($options['icon']);
        empty($options['color'])       ? : $note->setColor($options['color']);
        empty($options['badge'])       ? : $note->setBadge($options['badge']);
        empty($options['clickAction']) ? : $note->setClickAction('clickAction');
        empty($options['sound'])       ? : $note->setSound('sound');
        empty($options['tag'])         ? : $note->setTag('tag');
        empty($options['body'])        ? : $note->setBody('body');


        $message = new Message();
        $message->setNotification($note);
        $message->setData($options['data']);

        return $message;
    }



    /**
     * @param array|\Traversable $options
     */
    protected function checkOptions($options)
    {
        if(empty($options['title'])){
            throw new \InvalidArgumentException;
        }


        if(empty($options['type']) || !in_array($options['type'], [self::DEVICE, self::TOPIC] ) ){
            throw new \InvalidArgumentException;
        }



        if(empty($options['typeIdentifier']) ){
            throw new \InvalidArgumentException;
        }
    }


}
