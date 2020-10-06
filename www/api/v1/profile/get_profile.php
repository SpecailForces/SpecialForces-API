<?php
require_once __DIR__.'/../auth/settings.php';
require_once('../dbsettings.php');
require_once('../token_validator.php');

$input = json_decode(file_get_contents('php://input'),true);
$token = $server->getAccessTokenData(OAuth2\Request::createFromGlobals());
$res=array("success"=>false,"result"=>array());

try{
  if(empty($input['date']))
    $input['date'] = date("Y-m-d");
  
  $sql = "SELECT * FROM day_profile WHERE user_id = '$token[user_id]' AND date = '$input[date]';";
  $result = mysqli_query($dbconn,$sql);
  $row = mysqli_fetch_assoc($result);
  array_push($res['result'], $row);
  $res['success']=true;
}
catch(Exception $e){
  http_response_code(400); //bad request
  $res['message'] = $e->getMessage();
}
echo json_encode($res);
?>