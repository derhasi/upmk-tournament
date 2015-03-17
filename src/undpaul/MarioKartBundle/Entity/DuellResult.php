<?php

namespace undpaul\MarioKartBundle\Entity;


class DuellResult {

    public $duell;

    public $self;

    public $opponent;

    public $selfItem;

    public $opponentItem;

    public function __construct(Duell $duell, Participation $self) {
        $this->duell = $duell;
        $this->self = $self;
        $this->opponent = $duell->getOpponent($self);
        $this->selfItem = $duell->getSelfItem($self);
        $this->opponentItem = $duell->getOpponentItem($self);
    }

    /**
     * Check if  self has won.
     *
     * @return bool
     */
    public function isWin()
    {
        $a = $this->selfItem->getPosAbs();
        $b = $this->opponentItem->getPosAbs();

        if ($this->bothFinished()) {
            return ($this->selfItem->getPosAbs() < $this->opponentItem->getPosAbs());
        }
    }

    /**
     * Check if both have finished their race
     *
     * @return bool
     */
    public function bothFinished()
    {
        return $this->selfItem->getPosAbs() && $this->opponentItem->getPosAbs();
    }
}