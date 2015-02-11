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

    protected $clashes = array();

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
        return count($this->clashes);
    }

    public function clash($name) {
        $this->clashes[] = $name;
    }

}
