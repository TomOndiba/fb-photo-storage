<?php

// Here's 60 lines of crappy PHP code that'll upload photos to a default album
// and return the url to the photo page. You'll have to grab the image url itself
// if you want it to be REALLY persistent. If my secret/session keys break, feel free
// to substitute your own. Use auth.php to calculate your UserSessionKey and UserSecretKey 
// (follow the directions inside auth.php)

if(!empty($_POST)){
  $photobombUserSessionKey='68f7d12f383b69fce7685c3f-630945158';
  $photobombUserSecretKey='45355cd7640e0b17221f54d992353fbf';
  $appkey='d678757449ec0c4040b045b803c96f5c';
  $appsecret='f5d30da3c19f9cd7816d1bb0f7595a55';

  $filetypes = array("GIF", "JPG", "PNG", "PSD", "TIFF", "JP2", "IFF", "WBMP", "XBM");

  if (array_key_exists("error", $_FILES['uploadedfile']) && $_FILES['uploadedfile']["error"] != 0){
    switch($_FILES['uploadedfile']["error"]){
      case 1: echo "Filesize exceeds upload_max_filesize."; break;
      case 2: echo "Error: file size must be below 5MB."; break;
      case 3: echo "Error: only a partial file was uploaded. Try uploading again."; break;
      case 4: echo "Error: no file was uploaded."; break;
      case 6: echo "Error: temporary folder missing."; break;
      case 7: echo "Error: failed to write to disk."; break;
      case 8: echo "Error: PHP extension stopped file upload. Please try again."; break;
    }
    exit();
  }

  $info = pathinfo($_FILES['uploadedfile']['name']);

  if (!array_key_exists("extension", $info) || !(in_array(strtoupper($info["extension"]), $filetypes))){
    echo "Supported filetypes are: ".join(", ",$filetypes);
    exit();
  }

  require_once('facebook/facebook.php');
  require_once('facebook/facebook_desktop.php');

  // create the Facebook Object

  try {
    $fbObject = new FacebookDesktop($appkey, $appsecret);
    $fbObject->api_client->session_key = $photobombUserSessionKey;
    $fbObject->secret = $photobombUserSecretKey;
    $fbObject->api_client->secret = $photobombUserSecretKey;
    $fbUser = $fbObject->api_client->users_getLoggedInUser();
  } catch (Exception $e) {
    echo 'Could not use session key / log in user';
  }

  try {
    $fbReturn = $fbObject->api_client->photos_upload($_FILES['uploadedfile']['tmp_name'], NULL, '', $fbUser);
  } catch (Exception $e) {
    echo $e;
  }
  echo $fbReturn['link'];

}
else{
  echo '<form enctype="multipart/form-data" method="POST">';
  echo '<input type="hidden" name="MAX_FILE_SIZE" value="5000000" />';
  echo 'Choose a file to upload: <input name="uploadedfile" type="file" /><br />';
  echo '<input type="submit" value="Upload File" />';
  echo '</form>';
}
