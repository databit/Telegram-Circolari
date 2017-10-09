<?php
    $sTelegramAPIKey = '<TELEGRAM API KEY>'; // Get Key from https://core.telegram.org/api/obtaining_api_id
    $sChatID =  '@MyChat'; // @ChatName or ChatId (like -100XXXXXXXXXX)
    $sFilePath = 'letter.pdf';
    $sTitle = 'My Title';
    $sMessage = 'Lorem ipsum';
    $bSendFile = true;
    
    //TELEGRAM
    try {
        include "telegram.php";
        $mTelegram = new Telegram($sTelegramAPIKey);

        if($bSendFile)
            $mTelegram->sendDocument('http://'. $_SERVER['HTTP_HOST'] .'/'. $sFilePath, $sTitle, $sChatID);
        else
            $mTelegram->sendMessage($sMessage, $sChatID);


    } catch (Exception $e) {}
    // END TELEGRAM
?>