<?php

namespace undpaul\MarioKartBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
    private $pos_rel;

    /**
     * @var integer
     */
    private $pos_abs;

    /**
     * @var integer
     */
    private $pts_rel;

    /**
     * @var integer
     */
    private $pts_abs;

    /**
     * @var \undpaul\MarioKartBundle\Entity\Race
     */
    private $race;

    /**
     * @var \undpaul\MarioKartBundle\Entity\Player
     */
    private $player;


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
     * Set player
     *
     * @param \undpaul\MarioKartBundle\Entity\Player $player
     * @return RaceResultItem
     */
    public function setPlayer(\undpaul\MarioKartBundle\Entity\Player $player = null)
    {
        $this->player = $player;

        return $this;
    }

    /**
     * Get player
     *
     * @return \undpaul\MarioKartBundle\Entity\Player 
     */
    public function getPlayer()
    {
        return $this->player;
    }
}
