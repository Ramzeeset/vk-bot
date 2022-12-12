<?php

namespace CallBack;

const CALLBACK_API_EVENT_CONFIRMATION = 'confirmation';
const CALLBACK_API_EVENT_MESSAGE_NEW = 'message_new';

class CallBackApi {
    static function getEvent() {
        return json_decode(file_get_contents('php://input'), true);
    }

    static function sendResponse($data) {
        echo $data;
        exit();
    }

    static function handleConfirmationEvent() {
        self::sendResponse(CALLBACK_API_CONFIRMATION_TOKEN);
    }
}



