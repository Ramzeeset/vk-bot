<?php

namespace CallBack;

use Bot\Bot;
use Log\Logger;

const CALLBACK_API_EVENT_CONFIRMATION = 'confirmation';
const CALLBACK_API_EVENT_MESSAGE_EVENT = 'message_event';
const CALLBACK_API_EVENT_MESSAGE_NEW = 'message_new';

class CallBackApi {
    static function getEvent() {
        return json_decode(file_get_contents('php://input'), true);
    }

    static function sendResponse($data) {
        $message = new \Log\LogMessage();
        $message->appendLog('send response to user : ');
        $message->appendLog($data);
        $message->dumpLog();
        echo $data;
        exit();
    }

    static function okResponse() {
        echo "OK";
        exit();
    }

    static function handleConfirmationEvent() {
        Logger::dumpLog("call handleConfirmationEvent");
        self::sendResponse('2ed12a0e');
    }

    static function handleMessageEvent($data) {
        Logger::dumpLog("call handleMessageEvent");
        $user_id = $data['peer_id'];
        /**/;
        self::okResponse();
    }

    static function handleMessageNew($data) {
        Logger::dumpLog("call handleMessageNew");
        $user_id = $data['message']['peer_id'];
        Bot::sendMessage($user_id, $data);
        self::okResponse();
    }
}



