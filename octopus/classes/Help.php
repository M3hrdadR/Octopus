<?php


class Help
{
    // this func reforms +98... and 09... phone numbers to unique form.
    static function unique_phone($phone)
    {
        $phone = "09" . substr($phone, -9, 9);
        return $phone;
    }

    static function validate_phone($phone)
    {
        $phone = Help::unique_phone($phone);
        if (!(is_numeric($phone)))
            return false;
        return true;
    }

    static function validate_user($user, $token)
    {
        $array = $user->access_token;
        foreach ($array as $item) {
            if ($item->token == $token)
                return true;
        }
        return false;
    }

    static function validate_user_2($access)
    {
        $phone = $access["phone"];
        $token = $access["token"];
        // check if user phone exist in data base
        $search_user = new User();
        $search_user->phone = $phone;
        $users = $search_user->search();

        if (!$users)
            return false;

        $user = $users[0];
        $array = $user->access_token;
        foreach ($array as $item) {
            if ($item->token == $token)
                return true;
        }
        return false;
    }

    static function verify_username($var)
    {
        for ($i = 0; $i < strlen($var); $i++) {
            if (!((48 <= ord($var[$i]) and ord($var[$i]) <= 57) or
                (65 <= ord($var[$i]) and ord($var[$i]) <= 90) or
                (97 <= ord($var[$i]) and ord($var[$i]) <= 122))) {
                return false;
            }
        }
        if (strlen($var) < 5 or strlen($var) > 20)
            return false;
        if (is_numeric($var))
            return false;
        return true;
    }

    static function verify_name($var)
    {
        if (!ctype_alpha($var))
            return false;
        if (strlen($var) > 60 or strlen($var) < 2)
            return false;
        return true;
    }

    static function verify_email($var)
    {
        if (!filter_var($var, FILTER_VALIDATE_EMAIL))
            return false;
        return true;
    }

    static function verify_bio($var){
        if (strlen($var) > 200)
            return false;
        return true;
    }

    static function verify_url($var){
        if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $var))
            return false;
        return true;
    }

    static function verify_birth_date($var){
        if (!is_numeric($var))
            return false;
        if ( strlen(strval($var)) > 10)
            return false;
        return true;
    }

    static function verify_gender($var){
        if (!is_integer($var))
            return false;
        if (!($var == 0 or $var == 1))
            return false;
        return true;
    }

    static function verify_national_id($var){
        if (!is_numeric($var))
            return false;
        if (strlen($var) != 10)
            return false;
        $sum = 0;
        $remainder = 0;
        for ($i = 0; $i < strlen($var) - 1; $i++) {
            $sum += intval($var[$i] * (10 - $i) );
        }
        $remainder = $sum % 11;
        if ($remainder <= 2){
            if ($remainder != intval($var[9]))
                return false;
        }
        else {
            if (11 - $remainder != intval($var[9]))
                return false;
        }
        return true;
    }
}