<?php

namespace undpaul\MarioKartBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Tournament
 */
class Tournament
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;


    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $contestants;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->contestants = new ArrayCollection();
    }

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
     * Set name
     *
     * @param string $name
     * @return Tournament
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add contestants
     *
     * @param \undpaul\MarioKartBundle\Entity\User $contestants
     * @return Tournament
     */
    public function addContestant(\undpaul\MarioKartBundle\Entity\User $contestants)
    {
        $this->contestants[] = $contestants;

        return $this;
    }

    /**
     * Remove contestants
     *
     * @param \undpaul\MarioKartBundle\Entity\User $contestants
     */
    public function removeContestant(\undpaul\MarioKartBundle\Entity\User $contestants)
    {
        $this->contestants->removeElement($contestants);
    }

    /**
     * Get contestants
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getContestants()
    {
        return $this->contestants;
    }
}
