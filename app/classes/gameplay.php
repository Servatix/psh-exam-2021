<?php

class Gameplay
{
    protected GameMatch $game_match;
    public bool $verbose = false;

    function __construct(
        public int $min_players = 0,
        public int $max_players = 10
    )
    {
        $this->game_match = new GameMatch;
    }

    function play()
    {
        if ($this->verbose) {
            echo PHP_EOL, 'New game is open.', PHP_EOL;
            $this->game_match->verbose = $this->verbose;
        }

        $this->queue();

        // *Se mandan una partida epicarda*

        $this->endGame();
    }

    function queue()
    {
        $user_list = new UserList;
        $ready = rand($this->min_players, $this->max_players);

        for ($i=0; $i < $ready; $i++) { 
            $this->game_match->addPlayer( $user_list->getUser() );
        }

        if ($this->verbose) {
            echo $ready, ' players joined.', PHP_EOL;
        }
    }

    function endGame()
    {
        $this->game_match->endMatch();

        if ($this->verbose) {
            echo PHP_EOL, str_repeat('-', 25), PHP_EOL;
        }
    }
}