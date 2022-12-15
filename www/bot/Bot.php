<?php

namespace Bot;

use VKApi\VKApi;

require_once 'keyboaeds.php';

class Bot {
    static function sendMessage($user_id, $data) {
        VKApi::messageSend($user_id, "kek");
    }
}
