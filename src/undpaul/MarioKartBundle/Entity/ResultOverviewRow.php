<?php

namespace undpaul\MarioKartBundle\Entity;

class ResultOverviewRow {

    /**
     * @var Player
     */
    public $player;

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
    public function __construct(Player $player) {
        $this->player = $player;
    }

    /**
     * Add the data of a result item to the overview row.
     *
     * @param \undpaul\MarioKartBundle\Entity\RaceResultItem $result
     * @throws \Exception
     */
    public function addResult(RaceResultItem $result)
    {
        if ($result->getPlayer()->getId() != $this->player->getId()) {
            throw new \Exception('Invalid player');
        }

        $this->pos_abs[] = $result->getPosAbs();
        $this->pos_rel[] = $result->getPosRel();
        $this->pts_abs[] = $result->getPtsAbs();
        $this->pts_rel[] = $result->getPtsRel();
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
}