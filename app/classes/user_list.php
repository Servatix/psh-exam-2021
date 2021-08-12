<?php

class UserList
{
    protected string $endpoint = 'https://randomuser.me/api/';
    protected CurlHandle $ch;

    function __construct()
    {
        $this->ch = curl_init($this->endpoint);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
    }

    function getUser(): GameUser
    {
        $user = $this->readList();

        return new GameUser( ...$user );
    }

    function readList(): array
    {
        $user_data = curl_exec($this->ch);

        if (!$user_data) {
            throw new Exception("Error connecting to user list server", 501);
        }
        
        [
            'results' => [[
                'login' => [ 'username' => $nickname ],
                'picture' => [ 'thumbnail' => $thumbnail ]
            ]]
        ] = json_decode($user_data, true);

        $thumbnail = (new UserImage($thumbnail))->getUri();

        return [ 'nickname' => $nickname, 'thumbnail' => $thumbnail ];
    }
}
