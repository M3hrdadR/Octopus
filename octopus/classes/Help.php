<?php


class Help{
    // this func reforms +98... and 09... phone numbers to unique form.
    static function unique_phone($phone){
        $phone = "09".substr($phone,-10,9);
        return $phone;
    }

    static function validate_phone($phone){
        $phone = Help::unique_phone($phone);
        if (!(is_numeric($phone)))
            return false;
        return true;
    }

    static function validate_user($user, $token){
        $array = $user->access_token;
        foreach ($array as $item){
            if($item->token==$token)
                return true;
        }
        return false;
    }


}