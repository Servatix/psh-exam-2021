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
        $db = PshDatabase::conn();

        $query = $db->prepare("SELECT uuid FROM users WHERE nickname=?");
        $query->execute([$this->nickname]);
        $uuid = $query->fetchColumn();

        if (!$uuid) {
            $uuid = $this->registerUser();
        }

        return $uuid;
    }

    function registerUser(): string
    {
        $db = PshDatabase::conn();

        $uuid = $db->query("SELECT UUID()")->fetchColumn();

        $query = $db->prepare("INSERT INTO users(uuid_bin, nickname, thumbnail) VALUES( UUID_TO_BIN( ? ), ?, ? )");
        $query->execute([$uuid, $this->nickname, $this->thumbnail]);

        return $uuid;
    }
}