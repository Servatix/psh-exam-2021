<?php

class Player
{
    public int $score = 0;

    function __construct(
        protected GameUser $user
    ) { }

    function setScore(int $max_score): int
    {
        $this->score = $this->calcScore($max_score);

        return $this->score;
    }

    function calcScore(int $max_score): int
    {
        $score = 0;
        $offset = 0;

        if ($max_score & 1) {
            $max_score--;
            $offset = rand(0, 1);
        }

        $max_score /= 2;

        $score += rand(0, $max_score);
        $score += rand(0, $max_score);
        $score += $offset;

        return $score;
    }

    function getStatistics(): array
    {
        return [
            'uuid' => $this->user->uuid,
            'score' => $this->score
        ];
    }
}
