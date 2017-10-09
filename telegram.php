<?php
class Telegram{
    private static $_sUrl = "https://api.telegram.org/bot%s/%s";
    private $_sApiKey, $_sChatId;
    private $_sError;
    
    public function __construct($sApiKey, $sChatId = null){
        $this->_sApiKey = $sApiKey;
        $this->_sChatId = $sChatId;
        $this->_bUseCurl = false;
    }
    
    public function sendMessage($sText, $sChatID = null){
        if(empty($sChatID))
            $sChatID = $this->_sChatId;
            
        return $this->_callMethod(sprintf(Telegram::$_sUrl, $this->_sApiKey, 'sendMessage'), array('chat_id'   => $sChatID, 'text' => $sText));      
    }

    public function sendPhoto($sPhotoPath, $sCaption, $sChatID = null){
        $aData = array(
            'chat_id' => empty($sChatID) ? $this->_sChatId : $sChatID,
            'photo' => '@'. $sPhotoPath,
            'caption' => $sCaption
        );

        return $this->_callMethod(sprintf(Telegram::$_sUrl, $this->_sApiKey, 'sendPhoto'), $aData);      
    }
    
    public function sendDocument($sDocPath, $sCaption, $sChatID = null){
        $aData = array(
            'chat_id' => empty($sChatID) ? $this->_sChatId : $sChatID,
            'document' => $sDocPath,
            'caption' => $sCaption
        );

        return $this->_callMethod(sprintf(Telegram::$_sUrl, $this->_sApiKey, 'sendDocument'), $aData);      
    }
    
    private function _callMethod($sUrl, $aParams){
        if($this->_bUseCurl){
            $mCurl = curl_init();
            
            curl_setopt_array($mCurl, array(
                        CURLOPT_URL => $sUrl,
                        CURLOPT_POST => count($aParams),
                        CURLOPT_HEADER => 1,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_POSTFIELDS => http_build_query($aParams),
                        CURLOPT_SSL_VERIFYPEER => false
                ));
            $sResult = curl_exec($mCurl);
        
        } else {
            $sResult = file_get_contents($sUrl .'?'. http_build_query($aParams));
        }

        if($sResult === false)
            $this->_sError = ($this->_bUseCurl) ? curl_error($mCurl) : "Error";

        else
            $sResult = json_decode($sResult, true);
            
        if($this->_bUseCurl)
            curl_close($mCurl);
        
        return $sResult;
    }
    
    public function getError(){
        return $this->_sError;
    }
}
?>