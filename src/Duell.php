<?php

namespace derhasi\upmkTournament;


class Duell {

    /**
     * @var Contestant[]
     */
    protected $contestants;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var int
     */
    protected $count = 0;

    public function __construct(array $contestants) {
        $this->contestants = $contestants;
    }

    public function getID() {
        if (!isset($this->id)) {

            $ids = array();
            foreach ($this->contestants as $cont) {
                $ids[] = $cont->getId();
            }
            sort($ids);
            $this->id = implode(':', $ids);
        }

        return $this->id;
    }

    public function getCount() {
        return $this->count;
    }

    public function add() {
        $this->count++;
    }

}
