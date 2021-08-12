<?php

class Player
{
    function __construct(
        protected GameUser $user
    ) { }

    function setScore(int $max_score): int
    {
        $player_score = floor(($max_score + 1) * lcg_value());
        $this->user->last_score = $player_score;
        return $player_score;
    }

    function getStatistics(): array
    {
        return [
            'uuid' => $this->user->uuid,
            'score' => $this->user->last_score
        ];
    }
}
