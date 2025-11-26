<?php

namespace App\Class\Auth;

use App\Core\Config;
use Google\Client;
use Google\Service\Oauth2 as ServiceOauth2;
use Google\Service\Oauth2\Userinfo;

class OnGoogleAuth
{
    public readonly Client $client;
    private Userinfo $data;

    public function __construct()
    {
        $this->client = new Client;
    }

    public function init(): void
    {
        if ((! empty(Config::$SOCIAL_LOGIN_GOOGLE['ID'])) &&
            (! empty(Config::$SOCIAL_LOGIN_GOOGLE['SECRET'])) &&
            (! empty(Config::$SOCIAL_LOGIN_GOOGLE['REDIRECT_URI']))
        ) {
            $this->client->setClientId(Config::$SOCIAL_LOGIN_GOOGLE['ID']);
            $this->client->setClientSecret(Config::$SOCIAL_LOGIN_GOOGLE['SECRET']);
            $this->client->setRedirectUri(Config::$SOCIAL_LOGIN_GOOGLE['REDIRECT_URI']);

            $this->client->addScope('email');
            $this->client->addScope('profile');
        }
    }

    public function authorized(string $dataCallback): bool
    {
        if (substr($dataCallback, 0, 6) == '?code=') {
            $token = $this->client->fetchAccessTokenWithAuthCode($_GET['code']); // ($dataCallback);
            if (isset($token['access_token'])) {
                $this->client->setAccessToken($token['access_token']);
                $googleService = new ServiceOauth2($this->client);
                $this->data = $googleService->userinfo->get();
                return true;
            }
        }

        return false;
    }

    public function getData(): Userinfo
    {
        return $this->data;
    }

    public function generateAuthLink(): string
    {
        return $this->client->createAuthUrl();
    }
}
