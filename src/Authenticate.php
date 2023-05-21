<?php

namespace JalalLinuX\Tntity;

use JalalLinuX\Tntity\Facades\Entities\Auth;
use JalalLinuX\Tntity\Interfaces\ThingsboardUser;

class Authenticate
{
    public static function fromUser(ThingsboardUser $user): string
    {
        $mail = $user->getThingsboardEmailAttribute();
        if ($token = Thingsboard::cache("users.{$mail}.token")) {
            return $token;
        }
        $token = Auth::login($mail, $user->getThingsboardPasswordAttribute())['token'];
        Thingsboard::cache("users.{$mail}.token", $token, now()->addMinutes(10));

        return $token;
    }
}
