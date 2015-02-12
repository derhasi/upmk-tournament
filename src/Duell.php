<?php

namespace derhasi\upmkTournament;


class Duell implements ItemInterface {

    /**
     * @var ContestantCollection
     */
    protected $contestants;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var Race[]
     */
    protected $clashes = array();

    public function __construct(ContestantCollection $contestants) {
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

    public function clash(Race $race) {
        $this->clashes[] = $race;
    }

    /**
     * @return \derhasi\upmkTournament\ContestantCollection
     */
    public function getContestants() {
        return $this->contestants;
    }

}
