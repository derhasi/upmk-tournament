<?php
/**
 * Created by PhpStorm.
 * User: derhasi
 * Date: 17.03.15
 * Time: 16:19
 */

namespace undpaul\MarioKartBundle\Entity;


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
        $this->result2 = $item1;
    }

    public function getParticipants()
    {
        $return = array();
        /* @var RaceResultItem $result */
        foreach ($this->results as $result) {
            $return[] = $result->getParticipation();
        }
        return $return;
    }

    public function getOpponent(Participation $p)
    {
        if ($this->result1->getParticipation() == $p) {
            return $this->result1->getParticipation();
        }
        elseif ($this->result2->getParticipation() == $p) {
            return $this->result2->getParticipation();
        }
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
        while (count($items)) {
            $item1 = array_shift($items);
            foreach ($items as $item2) {
                $return[] = new Duell($item1, $item2);
            }
        }
        return $return;
    }

}