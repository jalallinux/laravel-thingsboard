<?php

namespace JalalLinuX\Thingsboard;

use Illuminate\Support\Carbon;
use JalalLinuX\Thingsboard\Entities\Auth;
use JalalLinuX\Thingsboard\Interfaces\ThingsboardUser;

class Authenticate
{
    public static function fromUser(ThingsboardUser $user): string
    {
        $mail = $user->getThingsboardEmailAttribute();
        if ($token = Thingsboard::cache("users_{$mail}_token")) {
            return $token;
        }
        $token = Auth::instance()->login($mail, $user->getThingsboardPasswordAttribute())['token'];
        $expire = Carbon::createFromTimestamp(decodeJWTToken($token, 'exp'))->subMinutes(5);
        Thingsboard::cache("users_{$mail}_token", $token, $expire);

        return last(explode(' ', $token));
    }
}
