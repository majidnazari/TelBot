<?php
$flag=null;
if(!isset($argv[1]))
{   
    die("please enter argument enable/disable\n");
}
if(($argv[1]==="enable"))
{
    $flag=true;
}
elseif($argv[1]==="disable")
{
    $flag=false;
}
else
{
    die("please enter just enable/disable.\n");
}
include 'conf.php';
$token="5092567192:AAEuiMoP624GLVb4I1udosX4rGAsnZfnF7o";
$url = "https://api.telegram.org/bot" . $token."/setChatPermissions?";
$sendUrl="https://api.telegram.org/bot" . $token."/sendMessage?";

$response=setPermission($chat_id,$url,$flag);
if($flag)
{
    $content["text"]="SuperGroup Will Be Enabled.";    
    $sendresponse=sendToArray($chat_id, $sendUrl, $content);
    //die("result is:".$sendresponse);
}
else{
    $content["text"]="SuperGroup Will Be Disabled.";    
    $sendresponse=sendToArray($chat_id, $sendUrl, $content);
}

die(($flag==1 ? " enabled " : "disabled") . " set successfully.\n");
//die("result is:".json_encode($response));

function setPermission($chat_id,$url,$flag)
{
    $ChatPermissions =json_encode( [
        "can_send_messages"=>$flag, 
        // "can_send_media_messages"=>false,
        // "can_send_other_messages"=>false,
        // "can_add_web_page_previews"=>false,
        // "can_send_polls"=>false,
        // "can_change_info"=>false,
        // "can_invite_users"=>false,
        // "can_pin_messages"=>false      
    ]);    

    $content['chat_id'] = $chat_id;    
    $content["permissions"]= $ChatPermissions;
    $turl = $url;
    $turl .=http_build_query($content); 
    $out[] = teleCurl($turl);
    return $out;
}
function sendToArray($chat_id,$sendUrl, $content) {
    $out = [];
   // foreach ($to_array as $chat_id) {
        $turl = $sendUrl;
        $content['chat_id'] = $chat_id;
        $turl .=http_build_query($content);
        $out[] = teleCurl($turl);
    //}
    return $out;
}
 function teleCurl($url) {    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}