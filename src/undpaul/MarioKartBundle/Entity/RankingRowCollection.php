<?php

namespace undpaul\MarioKartBundle\Entity;

class RankingRowCollection extends \Doctrine\Common\Collections\ArrayCollection {

    public function sort() {
        $elements = $this->toArray();
        RankingRow::sort($elements);
        return new RankingRowCollection($elements);
    }

}