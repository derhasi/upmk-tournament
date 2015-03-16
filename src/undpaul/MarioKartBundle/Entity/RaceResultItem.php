<?php

namespace undpaul\MarioKartBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use undpaul\MarioKartBundle\Service\PointRules;

/**
 * RaceResultItem
 */
class RaceResultItem
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $pos_rel = 0;

    /**
     * @var integer
     */
    private $pos_abs = 0;

    /**
     * @var integer
     */
    private $pts_rel = 0;

    /**
     * @var integer
     */
    private $pts_abs = 0;

    /**
     * @var \undpaul\MarioKartBundle\Entity\Race
     */
    private $race;

    /**
     * @var \undpaul\MarioKartBundle\Entity\Participation
     */
    private $participation;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get pos_rel
     *
     * @return integer 
     */
    public function getPosRel()
    {
        return $this->pos_rel;
    }

    /**
     * Set pos_abs
     *
     * @param integer $posAbs
     * @return RaceResultItem
     */
    public function setPosAbs($posAbs)
    {
        $this->pos_abs = $posAbs;

        return $this;
    }

    /**
     * Get pos_abs
     *
     * @return integer 
     */
    public function getPosAbs()
    {
        return $this->pos_abs;
    }

    /**
     * Set pts_rel
     *
     * @param integer $ptsRel
     * @return RaceResultItem
     */
    public function setPtsRel($ptsRel)
    {
        $this->pts_rel = $ptsRel;

        return $this;
    }

    /**
     * Get pts_rel
     *
     * @return integer 
     */
    public function getPtsRel()
    {
        return $this->pts_rel;
    }

    /**
     * Set pts_abs
     *
     * @param integer $ptsAbs
     * @return RaceResultItem
     */
    public function setPtsAbs($ptsAbs)
    {
        $this->pts_abs = $ptsAbs;

        return $this;
    }

    /**
     * Get pts_abs
     *
     * @return integer 
     */
    public function getPtsAbs()
    {
        return $this->pts_abs;
    }

    /**
     * Set race
     *
     * @param \undpaul\MarioKartBundle\Entity\Race $race
     * @return RaceResultItem
     */
    public function setRace(\undpaul\MarioKartBundle\Entity\Race $race = null)
    {
        $this->race = $race;

        return $this;
    }

    /**
     * Get race
     *
     * @return \undpaul\MarioKartBundle\Entity\Race 
     */
    public function getRace()
    {
        return $this->race;
    }

    /**
     * Get player
     *
     * @return \undpaul\MarioKartBundle\Entity\Player 
     */
    public function getPlayer()
    {
        return $this->participation->getPlayer();
    }

    /**
     * Calculates or recalculates the item in relation to items of the race.
     *
     * @param \undpaul\MarioKartBundle\Service\PointRules $rules
     */
    public function calculate(Pointrules $rules)
    {
        $this->calculatePosition();
        $this->calculatePoints($rules);
    }

    /**
     * Lifecycle callback to calculate positions.
     */
    protected function calculatePosition()
    {
        if (empty($this->pos_abs)) {
            $this->pos_rel = 0;
            return;
        }

        // We start on Position one and check the number of result items that
        // are positioned in front of us.
        $pos_rel = 1;

        /**
         * @var RaceResultItem $result
         */
        foreach ($this->race->getResults() as $result) {
            if ($result->getPosAbs() && $result->getPosAbs() < $this->pos_abs) {
                $pos_rel++;
            }
        }

        $this->pos_rel = $pos_rel;
    }

    /**
     * Lifecycle callback to calulcate points based on the position.
     */
    protected function calculatePoints(PointRules $rules)
    {
        $this->pts_abs = $rules->getAbsolutePoint($this->pos_abs);
        $this->pts_rel = $rules->getRelativePoint($this->pos_rel);
    }

    /**
     * Generate a new race result item.
     *
     * @param \undpaul\MarioKartBundle\Entity\Race $race
     * @param \undpaul\MarioKartBundle\Entity\Player $player
     * @return \undpaul\MarioKartBundle\Entity\RaceResultItem
     */
    public static function generate(Race $race, Player $player)
    {
        $item = new RaceResultItem();
        $item->setRace($race)
            ->setPlayer($player);
        return $item;
    }

    /**
     * Set pos_rel
     *
     * @param integer $posRel
     * @return RaceResultItem
     */
    public function setPosRel($posRel)
    {
        $this->pos_rel = $posRel;

        return $this;
    }

    /**
     * Set participation
     *
     * @param \undpaul\MarioKartBundle\Entity\Participation $participation
     * @return RaceResultItem
     */
    public function setParticipation(\undpaul\MarioKartBundle\Entity\Participation $participation = null)
    {
        $this->participation = $participation;

        return $this;
    }

    /**
     * Get participation
     *
     * @return \undpaul\MarioKartBundle\Entity\Participation 
     */
    public function getParticipation()
    {
        return $this->participation;
    }
}
