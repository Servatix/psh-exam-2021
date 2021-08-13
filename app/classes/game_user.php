<?php

class GameUser {
    public string $uuid;

    function __construct(
        public string $nickname,
        public string $thumbnail
    ) {
        $this->uuid = $this->getUUID();
    }

    function getUUID(): string
    {
        $db = new PshDatabase;

        $uuid = $db->findUserIdByNick($this->nickname);

        if (!$uuid) {
            $uuid = $db->createUser($this->nickname, $this->thumbnail);
        }

        return $uuid;
    }
}