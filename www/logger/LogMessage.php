<?php

namespace Log;

class LogMessage
{
    public $message;

    public function __construct()
    {
        $this->message = "";
    }

    public function appendLog($log)
    {
        if (is_array($log)) {
            $this->message = json_encode($log);
        } else {
            $this->message .= $log;
        }
    }

    public function dumpLog()
    {
        $trace = debug_backtrace();
        $function_name = isset($trace[2]) ? $trace[2]['function'] : '-';
        $mark = date("H:i:s") . ' [' . $function_name . ']';

        $log_name = BOT_LOGS_DIRECTORY . '/log_' . date("j.n.Y") . '.txt';
        file_put_contents($log_name, $mark . " : " . $this->message . "\n", FILE_APPEND);
    }
}