<?php

namespace undpaul\MarioKartBundle\Entity;

/**
 * Holds information for a player participation in a ranking context.
 */
class DuellRankingRow {

    /**
     * @var Participation
     */
    protected $opponent;

    protected $results;

    protected $wins = 0;

    protected $losses = 0;

    protected $unfinished = 0;

    protected $total = 0;

    /**
     * Constructor.
     *
     * @param \undpaul\MarioKartBundle\Entity\Player $player
     */
    public function __construct(Participation $opponent) {
        $this->opponent = $opponent;
    }

    /**
     * Getter for associated participation.
     *
     * @return \undpaul\MarioKartBundle\Entity\Participation
     */
    public function getOpponent()
    {
        return $this->opponent;
    }

    public function getWins()
    {
        return $this->wins;
    }

    public function getLosses()
    {
        return $this->losses;
    }

    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Add the data of a result item to the overview row.
     *
     * @param \undpaul\MarioKartBundle\Entity\RaceResultItem $result
     * @throws \Exception
     */
    public function addDuellResult(DuellResult $result)
    {
        if ($result->opponent != $this->opponent) {
            throw new \Exception('Invalid opponent');
        }

        $this->results[] = $result;

        if (!$result->bothFinished()) {
            $this->unfinished++;
        }
        elseif ($result->isWin()) {
            $this->wins++;
        }
        else {
            $this->losses++;
        }

        $this->total++;
    }

    /**
     * Sorts an array of result rows.
     *
     * @param RankingRow[] $arr
     */
    public static function sort(array &$arr)
    {
        uasort($arr, '\undpaul\MarioKartBundle\Entity\RankingRow::sortCallback');
    }

    /**
     * Callback function for use in usort() like functions.
     *
     * @param \undpaul\MarioKartBundle\Entity\RankingRow $a
     * @param \undpaul\MarioKartBundle\Entity\RankingRow $b
     * @return int
     */
    public static function sortCallback(RankingRow $a, RankingRow $b)
    {
        $diff = $a->getSumRelative() - $b->getSumRelative();
        if ($diff != 0) {
            return -1 * $diff;
        }
        $diff = $a->getSumAbsolute() - $b->getSumAbsolute();
        if ($diff != 0) {
            return -1 * $diff;
        }
        $diff = $a->getFinishedCount() - $b->getFinishedCount();
        if ($diff != 0) {
            return -1 * $diff;
        }

        // Finally sort randomly.
        return rand(-1, 1);
    }
}