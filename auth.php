<?php

// Replace the data below, calculate your keys and insert them into index.php if the provided keys in index.php stop working.
// Before you authenticate with your own keys, make SURE that your app is set to "desktop" rather than "webapp" in the settings,
// otherwise php will complain about there being no 'secret' in the returned session.

require_once('facebook/facebook.php');
require_once('facebook/facebook_desktop.php');
$appkey='d678757449ec0c4040b045b803c96f5c';
$appsecret='f5d30da3c19f9cd7816d1bb0f7595a55';
    
$fbObject = new FacebookDesktop($appkey, $appsecret);
$session = $fbObject->do_get_session("BFNP4B"); // replace this with the data you get from http://www.facebook.com/code_gen.php?v=1.0&api_key=$appkey

$photobombUserSessionKey = $session['session_key'];
$photobombUserSecretKey = $session['secret'];
echo $photobombUserSessionKey.'\n';
echo $photobombUserSecretKey.'\n';
try {
  $fbObject->api_client->session_key = $photobombUserSessionKey;
  $fbObject->secret = $photobombUserSecretKey;
  $fbObject->api_client->secret = $photobombUserSecretKey;
  $fbUser = $fbObject->api_client->users_getLoggedInUser();
  $fbReturn = $fbObject->api_client->users_getInfo($fbUser,array('name'));
} catch (Exception $e) {
  echo 'Invalid AUTH code / could not generate session key';
}
