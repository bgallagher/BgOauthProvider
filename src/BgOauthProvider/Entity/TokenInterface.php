<?php

namespace BgOauthProvider\Entity;

use ZfcUser\Entity\User;

interface TokenInterface
{

    const TOKEN_REQUEST = 1;
    const TOKEN_ACCESS = 2;

    public function getId();

    public function setType($type);

    public function getType();

    public function setToken($token);

    public function getToken();

    public function setTokenSecret($tokenSecret);

    public function getTokenSecret();

    public function setCallbackUrl($callbackUrl);

    public function getCallbackUrl();

    public function setVerifier($verifier);

    public function getVerifier();

    public function setApp($app);

    public function getApp();

    public function setUser(User $user);

    public function getUser();

}
