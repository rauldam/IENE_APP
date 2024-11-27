<?php

require_once($_SERVER["DOCUMENT_ROOT"].'/api/v1/movil/libs/vendor/autoload.php');
$guzzle = new \GuzzleHttp\Client();
$token = 'ZTc0NGE4MTktZWRlMS00MjVlLTg4NGMtYmQ2MDAyYzQwYWRl';
$app_id = 'f9abea19-9d38-4b81-bbfe-7f1ce0d56f58';
$contents = array();
$appId = array();
$body = array();
$segment = null;
if($segment == null){
    $included_segments = array("Active Subscriptions");
}else{
    $included_segments = array($segment);
}
$contents['en'] = $_POST['notificacion'];
$appId = $app_id;
$body['included_segments'] = $included_segments;
$body['contents'] = $contents;
$body['app_id'] = $appId;
$send = json_encode($body);
        
$response = $guzzle->request('POST', 'https://onesignal.com/api/v1/notifications', [
  'body' => $send,
  'headers' => [
    'Authorization' => 'Basic '.$token,
    'accept' => 'application/json',
    'content-type' => 'application/json',
  ],
]);

echo $response->getBody()->getContents();
    

?>