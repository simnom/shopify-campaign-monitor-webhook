<?php
require_once '../vendor/campaignmonitor/createsend-php/csrest_subscribers.php';

$config = [
  'api_key' => 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx',
  'list_id' => 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx',
  'shopify_domain' => [
  'your-shop-name.myshopify.com',
  'any-other-domains.com'
  ]
];

###
# Check the headers to make sure it's come from the
# correct shopify domain
###
$headers = apache_request_headers();

if (! in_array($headers['X-Shopify-Shop-Domain'], $config['shopify_domain'])) {
  header('HTTP/1.1 403 Forbidden');
  exit(0);
}

###
# Process the webhook content
###
$webhookContent = "";

$webhook = fopen('php://input' , 'rb');
while (!feof($webhook)) {
  $webhookContent .= fread($webhook, 4096);
}
fclose($webhook);

$obj = json_decode($webhookContent);

###
# Send the request to Campaign Monitor
###
if (isset($obj->accepts_marketing) && $obj->accepts_marketing === true) {
  $auth = array('api_key' => $config['api_key']);
  $wrap = new CS_REST_Subscribers($config['list_id'], $auth);

  $result = $wrap->add(array(
    'EmailAddress' => $obj->email,
    'Name' => $obj->first_name . ' ' . $obj->last_name,
    'Resubscribe' => true
    ));

  $body = "Result of POST /api/v3.1/subscribers/{list id}.{format}\n<br />";

  if($result->was_successful()) {
    exit;
  } else {
    $body .= 'Failed with code '.$result->http_status_code."\n";
  }
}

