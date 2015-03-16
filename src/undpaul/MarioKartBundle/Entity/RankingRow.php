<?php

namespace undpaul\MarioKartBundle\Entity;

/**
 * Holds information for a player participation in a ranking context.
 */
class RankingRow {

    /**
     * @var Participation
     */
    protected $participation;

    /**
     * @var array
     */
    public $pos_rel = array();

    /**
     * @var array
     */
    public $pos_abs = array();

    /**
     * @var array
     */
    public $pts_rel = array();

    /**
     * @var array
     */
    public $pts_abs = array();

    /**
     * Constructor.
     *
     * @param \undpaul\MarioKartBundle\Entity\Player $player
     */
    public function __construct(Participation $participation) {
        $this->participation = $participation;
    }

    /**
     * Add the data of a result item to the overview row.
     *
     * @param \undpaul\MarioKartBundle\Entity\RaceResultItem $result
     * @throws \Exception
     */
    public function addResult(RaceResultItem $result)
    {
        if ($result->getParticipation()->getId() != $this->participation->getId()) {
            throw new \Exception('Invalid player');
        }

        $this->pos_abs[] = $result->getPosAbs();
        $this->pos_rel[] = $result->getPosRel();
        $this->pts_abs[] = $result->getPtsAbs();
        $this->pts_rel[] = $result->getPtsRel();
    }

    /**
     * Get the number of races the participant finished.
     *
     * @return int
     */
    public function getFinishedCount()
    {
        return count(array_filter($this->pos_abs));
    }

    /**
     * Get sum of absolute points.
     *
     * @return number
     */
    public function getSumAbsolute()
    {
        return array_sum($this->pts_abs);
    }

    /**
     * Get sum of relative points.
     *
     * @return number
     */
    public function getSumRelative()
    {
        return array_sum($this->pts_rel);
    }

    /**
     * Retrieve player for result row.
     *
     * @return \undpaul\MarioKartBundle\Entity\Player
     */
    public function getPlayer()
    {
        return $this->participation->getPlayer();
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

        // Finally sort by name.
        return 2 * ($a->getPlayer()->getName() > $b->getPlayer()->getName()) -1;
    }
}