<?php

namespace VKApi;

use Exception;
use Log\Logger;

require_once 'config.php';

class VKApi {
    static function messageSend($peer_id, $message, $keyboard = [], $attachments = []) {
        Logger::dumpLog("call messageSend");
        return self::apiCall('messages.send', array(
            'peer_id'    => $peer_id,
            'message'    => $message,
            'keyboard'    => json_encode($keyboard, JSON_UNESCAPED_UNICODE),
            'attachment' => implode(',', $attachments)
        ));
    }

    private static function apiCall($method, $params = array()) {
        Logger::dumpLog("api call start");
        $params['access_token'] = VK_API_ACCESS_TOKEN;
        $params['v'] = VK_API_VERSION;
        $params['random_id'] = '0';

        $query = http_build_query($params);
        $url = VK_API_ENDPOINT.$method.'?'.$query;
        Logger::dumpLog($url);

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $json = curl_exec($curl);
        $error = curl_error($curl);
        if ($error) {
            Logger::dumpLog("error on api call : " . $error);
            throw new Exception("Failed {$method} request");
        }

        curl_close($curl);

        $response = json_decode($json, true);
        if (!$response || !isset($response['response'])) {
            Logger::dumpLog("error on api call : " . $error);
            throw new Exception("Invalid response for {$method} request");
        }

        Logger::dumpLog("api call finish");
        return $response['response'];

    }
}