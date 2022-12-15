<?php

require 'vendor/autoload.php';

$event = CallBack\CallBackApi::getEvent();

switch ($event["type"]) {
    case CallBack\CALLBACK_API_EVENT_CONFIRMATION:
        CallBack\CallBackApi::handleConfirmationEvent();
        break;
    case CallBack\CALLBACK_API_EVENT_MESSAGE_EVENT:
        CallBack\CallBackApi::handleMessageEvent($event['object']);
        break;
    case CallBack\CALLBACK_API_EVENT_MESSAGE_NEW:
        CallBack\CallBackApi::handleMessageNew($event['object']);
        break;
    default:
        CallBack\CallBackApi::sendResponse('Unsupported event '.$event['type']);
        break;
}


