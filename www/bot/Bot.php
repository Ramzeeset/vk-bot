<?php

namespace Bot;

use Log\Logger;
use VKApi\VKApi;

require_once 'keyboaeds.php';

class Bot {
    private static $MAIN_STATE = 2;
    private static $SELECT_NEEDED_STATE = 3;
    private static $SELECT_REMOVED_STATE = 4;

    static function sendMessage($user_id, $data) {
        $db = mysqli_connect("localhost", "root", "", "products");
        if ($db === false) {
            Logger::dumpLog(mysqli_connect_error());
            self::processError($user_id);
            return;
        }
        $state = self::getUserState($user_id, $db);
        if ($state === null) {
            Logger::dumpLog("register new user");
            self::setUserState($user_id, self::$MAIN_STATE, $db);
            $state = "main";
        }
        switch ($state) {
// todo implement this
//            case "hello":
//                self::processHelloState($user_id, $data);
//                break;
//            case "connect":
//                self::processConnectState($user_id, $data);
//                break;
            case "main":
                self::processMainState($user_id, $data, $db);
                break;
            case "select needed":
                self::processSelectNeededState($user_id, $data, $db);
                break;
            case "select removed":
                self::processSelectRemovedState($user_id, $data, $db);
                break;
        }
    }

    static function processHelloState($user_id, $data) {
        switch ($data['message']['text']) {
            case "Начать":
                $message = "Привет";
                VKApi::messageSend($user_id, $message, HELLO_KEYBOARD);
                break;
            case "Создать комнату":
                $message = "Придумай пароль для комнаты";
                /* state -> select*/
                VKApi::messageSend($user_id, $message, SELECT_KEYBOARD);
                break;
            case "Подключиться к комнате":
                $message = "Введи пароль для комнаты";
                /* state -> select*/
                VKApi::messageSend($user_id, $message, SELECT_KEYBOARD);
                break;
            default:
                $message = "";
                VKApi::messageSend($user_id, $message, HELLO_KEYBOARD);
        }
    }

    static function processConnectState($user_id, $data) {}

    static function processMainState($user_id, $data, $db) {
        switch ($data['message']['text']) {
            case "Список нужного":
                $message = self::getProductsOfRoom(1, $db);
                if ($message === []) {
                    $message = "Пока что ничего не нужно";
                } else {
                    $message = implode("\n", $message);
                }
                VKApi::messageSend($user_id, $message, MAIN_KEYBOARD);
                break;
            case "Добавить":
                $message = "Напиши, чего не хватает";
                self::updateUserState($user_id, self::$SELECT_NEEDED_STATE, $db);
                VKApi::messageSend($user_id, $message, SELECT_KEYBOARD);
                break;
            case "Убрать":
                $message = "Выбери, что купил";
                self::updateUserState($user_id, self::$SELECT_REMOVED_STATE, $db);
                VKApi::messageSend($user_id, $message, self::getProductsKeyboard(1, $db));
                break;
            default:
                $message = "Выберете пункт из меню";
                VKApi::messageSend($user_id, $message, MAIN_KEYBOARD);
        }
    }

    static function processSelectNeededState($user_id, $data, $db) {
        switch ($data['message']['text']) {
            case "Назад":
                self::updateUserState($user_id, self::$MAIN_STATE, $db);
                VKApi::messageSend($user_id, "Возвращаемся к меню", MAIN_KEYBOARD);
                break;
            default:
                self::addProductToRoom(1, $data['message']['text'], $db);
                $message = "Записали : " . $data['message']['text'];
                VKApi::messageSend($user_id, $message, SELECT_KEYBOARD);
        }
    }

    static function processSelectRemovedState($user_id, $data, $db) {
        switch ($data['message']['text']) {
            case "Назад":
                self::updateUserState($user_id,self::$MAIN_STATE, $db);
                VKApi::messageSend($user_id, "Возвращаемся к меню", MAIN_KEYBOARD);
                break;
            default:
                $flag = self::removeProductToRoom(1, $data['message']['text'], $db);
                if ($flag) {
                    $message = "Удалили : " . $data['message']['text'];
                } else {
                    $message = "Этого не было в списке : ". $data['message']['text'];
                }
                VKApi::messageSend($user_id, $message,  self::getProductsKeyboard(1, $db));
        }
    }

    static function processError($user_id) {
        VKApi::messageSend($user_id, "Bot unavailable now. Call please Vlad Senin", MAIN_KEYBOARD);
    }

    static function addProductToRoom($room_id, $product_name, $db) {
        $query = 'insert into storage (room_id, name) VALUES (' . $room_id . ', "'  . $product_name .'")';
        Logger::dumpLog("query to storage " . $query);
        return mysqli_query($db, $query);
    }

    static function removeProductToRoom($room_id, $product_name, $db) {
        $query = 'delete from storage where room_id='.$room_id .' and name="'.$product_name.'"';
        Logger::dumpLog("query to storage " . $query);
        mysqli_query($db, $query);
        return mysqli_affected_rows($db) !== 0;
    }

    static function getProductsOfRoom($room_id, $db): array {
        $query = "select name from storage where room_id=".$room_id;
        Logger::dumpLog("query to storage " . $query);
        $result = mysqli_query($db, $query);
        $list = [];
        $product = mysqli_fetch_array($result);
        while ($product !== null) {
            $list[] = $product["name"];
            $product = mysqli_fetch_array($result);
        }
        return $list;
    }

    static function setUserState($user_id, $state, $db) {
        $query = 'insert into states (user_id, state) VALUES (' . $user_id . ', '  . $state .')';
        Logger::dumpLog("query to states " . $query);
        return mysqli_query($db, $query);
    }

    static function updateUserState($user_id, $state, $db) {
        $query = 'update states set state=' . $state . ' WHERE user_id=' . $user_id;
        Logger::dumpLog("query to states " . $query);
        return mysqli_query($db, $query);
    }

    static function getUserState($user_id, $db) {
        $query = "select state from states where user_id=".$user_id;
        Logger::dumpLog("query to states " . $query);
        $result = mysqli_query($db, $query);
        $fetch = mysqli_fetch_array($result);
        $state = $fetch["state"];
        if ($state === "2") {
            return "main";
        } else if ($state === "3") {
            return "select needed";
        } else if ($state === "4") {
            return "select removed";
        } else {
            return null;
        }
    }

    static function getProductsKeyboard($room_id, $db) {
        $list = self::getProductsOfRoom($room_id, $db);
        $buttons = [];
        for ($i = 0; $i < count($list); $i++) {
            $button = [
                ["action" =>
                    ["type" => "text",
                        "label" => $list[$i],
                        "payload" => ""],
                    "color" => "primary"
                ]
            ];
            $buttons[] = $button;
        }
        $buttons[] = [
            ["action" =>
                ["type" => "text",
                    "label" => "Назад",
                    "payload" => ""],
                "color" => "primary"
            ]
        ];
        $KEYBOARD = [];
        $KEYBOARD["one_time"] = false;
        $KEYBOARD["buttons"] = $buttons;
        return $KEYBOARD;
    }
}
