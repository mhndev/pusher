# Single Interface Multiple Pusher Service

single interface for using multiple pusher service

### Included Service

- FCM
- GCM
- Nginx Push Stream Module
- Pubnub.com
- Pusher.com


### Sample Usage 
```php

use mhndev\pusher\PusherFactory;

require 'vendor/autoload.php';


$httpClient = new \GuzzleHttp\Client();

$message = new \mhndev\pusher\Message([
    'name'   => 'majid',
    'family' => 'abdolhosseini',
    'age'    => 25
]);




//send message using pusher.com service
$pusherDotComService = PusherFactory::createPusher(
    PusherFactory::PUSHER_PUSHERDOTCOM,
    ['app_key', 'app_secret', 'app_id', []]
);
$pusherDotComService->push($message, 'device1');




//send message using pubnub.com service
$pubnub = PusherFactory::createPusher(
    PusherFactory::PUSHER_PUBNUB, 
    ['public_key', 'subscribe_key', 'secret_key']
);
$pubnub->push($message, 'device1');



//send message using FCM service
$fcm = PusherFactory::createPusher(
    PusherFactory::PUSHER_FCM, 
    ['api_key', $httpClient]
);
$fcm->push($message, 'device1');



//send message using GCM service
$gcm = PusherFactory::createPusher(
    PusherFactory::PUSHER_GCM,
    ['api_key']
);
$gcm->push($message, 'device1');



//send message using nginx push stream module
$nginx = PusherFactory::createPusher(
    PusherFactory::PUSHER_NGINXMODULE,
    [$httpClient, 'http://example.com:8000']
);
$nginx->push($message, 'device1');

```
