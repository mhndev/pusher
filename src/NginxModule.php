<?php
namespace mhndev\pusher;

use GuzzleHttp\Client;
use mhndev\pusher\exceptions\InvalidArgumentException;
use mhndev\pusher\exceptions\PushFailException;
use mhndev\pusher\interfaces\iMessage;
use mhndev\pusher\interfaces\iPusher;

/**
 * Class NginxModule
 * @package mhndev\pusher
 */
class NginxModule implements iPusher
{


    /**
     * @var mixed
     */
    protected $httpClient;

    /**
     * @var string
     */
    protected $endpoint;


    /**
     * PusherNginxModule constructor.
     * @param $httpClient
     * @param string $endpoint
     */
    public function __construct($httpClient, $endpoint)
    {
        $this->httpClient = $httpClient;
        $this->endpoint   = $endpoint;
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
        /** @var Client $client */
        $client = $this->httpClient;
        $message = addslashes($message->__toString());

        try{
            $response = $client->request(
                'POST',
                $this->endpoint.'?id='.$deviceIdentifier ,
                [ 'body' => $message ]
            );

            if($response->getStatusCode() != 200 ){
                throw new PushFailException($response->getBody()->getContents());
            }

        }
        catch (\Exception $e){
            throw new PushFailException($e->getMessage());
        }

    }

    /**
     * @param iMessage $message
     * @param array|\Traversable $deviceIdentifiers
     * @param array|\Traversable $options
     * @return mixed
     * @throws InvalidArgumentException
     * @throws PushFailException
     */
    function pushToMany(iMessage $message, $deviceIdentifiers, $options = [])
    {
        /** @var Client $client */
        $client = $this->httpClient;
        $message = addslashes($message->__toString());

        if(! is_array($deviceIdentifiers) && ! $deviceIdentifiers instanceof \Traversable){
            throw new InvalidArgumentException(
                'deviceIdentifiers argument (second argument) needed to be array or instance of \Traversable'
            );
        }

        foreach ($deviceIdentifiers as $deviceIdentifier){
            try{
                $response = $client->request(
                    'POST',
                    $this->endpoint.'?id='.$deviceIdentifier ,
                    [ 'body' => $message ]
                );

                if($response->getStatusCode() != 200 ){
                    throw new PushFailException($response->getBody()->getContents());
                }
            }

            catch (\Exception $e){
                throw new PushFailException($e->getMessage());
            }
        }
    }


    /**
     * @param iMessage $message
     * @param $topicName
     * @param array|\Traversable $options
     * @return mixed
     */
    function pushToTopic(iMessage $message, $topicName, $options = [])
    {
        // TODO: Implement pushToTopic() method.
    }
}
