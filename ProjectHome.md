This small library simplifies calls to the Freshbooks API. It is meant for inclusion in a Zend Framework project and follows the Zend naming conventions for autoloading classes.

A basic example:
```
$api =& Freshbooks_Api::getInstance();
$api->setAccountInformation( 'api_url', 'api_token' );
$result = $api->call('client.list');
if( !$result->isError() ){
    foreach($result->data->clients as $client){
        echo $client->first_name.' '.$client->last_name;
    }
}
```