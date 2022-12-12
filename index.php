<?php

require 'vendor/autoload.php';

$event = CallBack\CallBackApi::getEvent();

Log\Logger::dumpLog($event);

switch ($event["type"]) {
    case CallBack\CALLBACK_API_EVENT_CONFIRMATION:
        CallBack\CallBackApi::handleConfirmationEvent();
        break;
    case CallBack\CALLBACK_API_EVENT_MESSAGE_NEW:
        break;
    default;
}

echo "Please call letter";


