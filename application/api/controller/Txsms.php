<?php
namespace app\api\controller;

use Qcloud\Sms\SmsSingleSender;
use Qcloud\Sms\SmsMultiSender;
use Qcloud\Sms\SmsVoiceVerifyCodeSender;
use Qcloud\Sms\SmsVoicePromptSender;
use Qcloud\Sms\SmsStatusPuller;
use Qcloud\Sms\SmsMobileStatusPuller;

use Qcloud\Sms\VoiceFileUploader;
use Qcloud\Sms\FileVoiceSender;
use Qcloud\Sms\TtsVoiceSender;

class Txsms{

    public function sendSingleTemplateSms($phone,$params,$templateId,$smsSign){
        try {
            $ssender = new SmsSingleSender(config('txsms.appid'), config('txsms.appkey'));
            $result = $ssender->sendWithParam("86", $phone, $templateId,$params, $smsSign, "", "");
            $rsp = json_decode($result);
            return $rsp;
        } catch(\Exception $e) {
            return $e;
        }
    }
}