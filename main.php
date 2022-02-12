<?php
        include 'conf.php';
        $flag = "";
        $msgflag=null;
        if (!isset($argv[1])) {
            die("please enter argument enable/disable\n or instead enter argument shortmsg/longmsg \n");
        } 
        if (($argv[1] === "enable")) {
            $flag = "enable";
        }
        else if ($argv[1] === "disable") {
            $flag = "disable";
        }
        else if (($argv[1] === "shortmsg")) {
            $flag = "shortmsg";
        }
        else if ($argv[1] === "longmsg") {
            $flag = "longmsg";
        }
        else {
            die("please enter just enable/disable or shortmsg/longmsg to send message.\n");
        }

        //$token="5092567192:AAEuiMoP624GLVb4I1udosX4rGAsnZfnF7o";
        $mainUrl = "https://api.telegram.org/bot" . $mainToken . "/setChatPermissions?";
        //die($mainToken);
        $reportUrl = "https://api.telegram.org/bot" . $reportToken . "/sendMessage?";
        $arg2 = isset($argv[2]) ? $argv[2] : '';
       
        if ($flag==="enable") {
            $response = setPermission($mainChatId, $mainUrl, true);
            $content["text"] = "SuperGroup Will Be Enabled. $arg2";
            $sendresponse = sendToArray($reportChatId, $reportUrl, $content);    
            die(" enabled " . " set successfully.\n");
        } 
        else if ($flag==="disable") {
            $response = setPermission($mainChatId, $mainUrl, false);
            $content["text"] = "SuperGroup Will Be Disabled. $arg2";
            $sendresponse = sendToArray($reportChatId, $reportUrl, $content);  
            die(" disable " . " set successfully.\n");
        }
        else if ($flag==="shortmsg") {
            $responsePhoto = sendPhoto($mainChatId, $mainToken, $disable_file_id,$disableCaption);  
            $content["text"] = "send short msg to SuperGroup ";   
            $sendresponse = sendToArray($reportChatId, $reportUrl, $content); 
            die(" shortmsg " . " set successfully.\n");   
        } 
        else if($flag==="longmsg") { 
            $responsePhoto = sendPhoto($mainChatId, $mainToken, $enable_file_id,$enableCaption);        
            $content["text"] = "send long msg to SuperGroup "; 
            $sendresponse = sendToArray($reportChatId, $reportUrl, $content);  
            die(" longmsg " . " set successfully.\n");
        }
        else{
            die("there is a problem occured.\n");
        }
        

        function setPermission($mainChatId, $mainUrl, $flag)
        {
            $ChatPermissions = json_encode([
                "can_send_messages" => $flag,
                // "can_send_media_messages"=>false,
                // "can_send_other_messages"=>false,
                // "can_add_web_page_previews"=>false,
                // "can_send_polls"=>false,
                // "can_change_info"=>false,
                // "can_invite_users"=>false,
                // "can_pin_messages"=>false      
            ]);

            $content['chat_id'] = $mainChatId;
            $content["permissions"] = $ChatPermissions;
            $turl = $mainUrl;
            $turl .= http_build_query($content);
            $out[] = teleCurl($turl);
            return $out;
        }
        function sendToArray($reportChatId, $reportUrl, $content)
        {
            $out = [];
            // foreach ($to_array as $chat_id) {
            $turl = $reportUrl;
            $content['chat_id'] = $reportChatId;
            $turl .= http_build_query($content);
            $out[] = teleCurl($turl);
            //}
            return $out;
        }
        function teleCurl($url)
        {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            curl_close($ch);
            return $result;
        }
        function sendPhoto($chat_id, $mainToken, $file_id, $caption = '')
        {
            $url = "https://api.telegram.org/bot" . $mainToken . "/sendPhoto?chat_id=$chat_id"; 
            $curl = curl_init();
            $data = array(
                'chat_id' => $chat_id,
                'photo' => $file_id,
                'caption' => $caption
            );  
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                
                "Content-Type:multipart/form-data"
            ));
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);  
            $server_output = curl_exec($curl);  
            if (curl_error($curl)) {
                $error_msg = curl_error($curl);
                print_r($error_msg);
            }
            curl_close($curl);
            return $server_output;

        }
