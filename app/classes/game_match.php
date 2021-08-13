<?php

class GameMatch
{
    public string $finish_date;
    public array $players = array();
    public bool $verbose = false;

    function __construct(
        public int $max_score = 100
    ) { }

    function addPlayer(GameUser $user): void
    {
        $this->players[] = new Player($user);
    }

    function endMatch()
    {
        $max_score = $this->max_score;
        $this->finish_date = date("Y-m-d H:i:s");

        if ($this->verbose) {
            echo 'The game has ended at ', $this->finish_date, '.', PHP_EOL;
        }

        foreach ($this->players as $player) {
            $player_score = $player->setScore( $this->max_score );
            if ( $player_score == $max_score ) {
                $this->max_score--;
            }

            $stats = $player->getStatistics();
            $stats['date'] = $this->finish_date;

            $this->saveStatistics( ...$stats );

            if ($this->verbose) {
                echo 'New statistics added: ', var_export($stats, true), PHP_EOL;
            }
        }

    }

    function saveStatistics(string $uuid, int $score, string $date)
    {
        $db = new PshDatabase;

        $db->createNewStats($uuid, $score, $date);
    }
}