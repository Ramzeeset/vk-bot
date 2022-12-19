<?php

namespace Log;

const BOT_LOGS_DIRECTORY = "./logs";

class Logger {
    static function dumpLog($message) {
        $logMessage = new LogMessage();
        $logMessage->appendLog($message);
        $logMessage->dumpLog();
    }
}

