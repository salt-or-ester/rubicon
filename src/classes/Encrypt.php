<?php
class Encrypt {
    public static function encryptString($passwordList) {
        foreach ($passwordList as $password) {
            $salt = substr($password."H.",1,2);
            $salt = preg_replace("[^\.-z]",".",$salt);
            $salt = strtr($salt,":;<=>?@[\\]^_`","ABCDEFGabcdef"); 
            $encryptedPass = substr(crypt($password,$salt),-10);
            $encryptedStrings['encrypted'][$password] = $encryptedPass;
        }
        return json_encode($encryptedStrings);
    }
}
