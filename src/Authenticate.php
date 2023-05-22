<?php

namespace JalalLinuX\Tntity;

use Illuminate\Support\Carbon;
use JalalLinuX\Tntity\Facades\Entities\Auth;
use JalalLinuX\Tntity\Interfaces\ThingsboardUser;

class Authenticate
{
    public static function fromUser(ThingsboardUser $user): string
    {
        $mail = $user->getThingsboardEmailAttribute();
        if ($token = Thingsboard::cache("users_{$mail}_token")) {
            return $token;
        }
        $token = Auth::login($mail, $user->getThingsboardPasswordAttribute())['token'];
        $expire = Carbon::createFromTimestamp(decodeJWTToken($token, 'exp'))->subMinutes(5);
        Thingsboard::cache("users_{$mail}_token", $token, $expire);

        return $token;
    }
}
