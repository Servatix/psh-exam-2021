<?php

class GameLeaderboard
{
    function getStatistics(int $limit = 10): array
    {
        $db = new PshDatabase;

        $last_update = $db->getLastUpdate();

        $stats = $db->getTopScores($limit);

        array_walk(
            $stats, fn(&$row) => $row['thumbnail'] = (new UserImage($row['thumbnail']))->getUrl()
        );

        return array(
            'last_update' => $last_update,
            'leaderboard' => $stats
        );
    }
    
    function renderJSON(bool $return = false): ?string
    {
        $stats = json_encode( $this->getStatistics() );

        if ($return) {
            return $stats;
        }

        header('Content-type: application/json');

        print($stats);

        return null;
    }

    function renderCSV(bool $return = false): ?string
    {
        [
            'last_update' => $date,
            'leaderboard' => $stats
        ] = $this->getStatistics();

        $stream = fopen('php://temp', 'w+');

        fputcsv($stream, array_keys($stats[0]));

        foreach ($stats as $row) {
            fputcsv($stream, $row);
        }

        rewind($stream);

        if ($return) {

            $stats = stream_get_contents($stream);

            fclose($stream);

            return $stats;
        }

        $filename = 'psh_leaderboard_' . str_replace([' ', ':'], ['T', '.'], $date);

        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=$filename.csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        fpassthru($stream);

        fclose($stream);

        return null;
    }
}
