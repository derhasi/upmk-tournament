<?php
/**
 * Created by PhpStorm.
 * User: derhasi
 * Date: 17.03.15
 * Time: 16:19
 */

namespace undpaul\MarioKartBundle\Entity;


use Symfony\Component\Config\Definition\Exception\Exception;

class Duell {

    /**
     * @var RaceResultItem
     */
    private $result1;

    /**
     * @var RaceResultItem
     */
    private $result2;


    /**
     * @param \undpaul\MarioKartBundle\Entity\RaceResultItem $item1
     * @param \undpaul\MarioKartBundle\Entity\RaceResultItem $item2
     */
    public function __construct(RaceResultItem $item1, RaceResultItem $item2)
    {
        $this->result1 = $item1;
        $this->result2 = $item2;
    }

    /**
     * Check if the given duell has the given participant.
     *
     * @param \undpaul\MarioKartBundle\Entity\Participation $p
     *
     * @return bool
     */
    public function hasParticipaton(Participation $p)
    {
        return ($p->getId() == $this->result1->getParticipation()->getId()
          || $p->getId() == $this->result2->getParticipation()->getId());
    }

    /**
     * @return \undpaul\MarioKartBundle\Entity\Participation[]
     */
    public function getParticipants()
    {
        return array(
          $this->result1->getParticipation(),
          $this->result2->getParticipation(),
        );
    }

    /**
     * @param \undpaul\MarioKartBundle\Entity\Participation $p
     * @return \undpaul\MarioKartBundle\Entity\Participation
     */
    public function getOpponent(Participation $p)
    {
        $item = $this->getOpponentItem($p);
        if ($item) {
            return $item->getParticipation();
        }
    }

    /**
     * @param \undpaul\MarioKartBundle\Entity\Participation $p
     * @return \undpaul\MarioKartBundle\Entity\RaceResultItem
     */
    public function getOpponentItem(Participation $p)
    {
        if ($this->result1->getParticipation()->getId() == $p->getId()) {
            return $this->result2;
        }
        elseif ($this->result2->getParticipation()->getId() == $p->getId()) {
            return $this->result1;
        }

        throw new Exception('Invalid duell participant');
    }

    /**
     * @param \undpaul\MarioKartBundle\Entity\Participation $p
     * @return \undpaul\MarioKartBundle\Entity\RaceResultItem
     */
    public function getSelfItem(Participation $p)
    {
        if ($this->result1->getParticipation()->getId() == $p->getId()) {
            return $this->result1;
        }
        elseif ($this->result2->getParticipation()->getId() == $p->getId()) {
            return $this->result2;
        }

        throw new Exception('Invalid duell participant');
    }

    /**
     * Generate duells from race result items.
     *
     * @param RaceResultItem[] $items
     *
     * @return Duell[]
     */
    public static function generateDuells($items)
    {
        $return = array();
        while (count($items) > 1) {
            $item1 = array_shift($items);
            foreach ($items as $item2) {
                $return[] = new Duell($item1, $item2);
            }
        }
        return $return;
    }

}