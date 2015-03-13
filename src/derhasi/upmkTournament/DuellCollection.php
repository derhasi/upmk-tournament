<?php

namespace derhasi\upmkTournament;

class DuellCollection extends CollectionBase {

    /**
     * @var ContestantCollection
     */
    protected $contestantCollection;

    /**
     * @param ContestantCollection $contestants
     * @param Duell[] $duells
     */
    public function __construct(ContestantCollection $contestant, $duells = NULL) {
        $this->contestantCollection = $contestant;

        // First place the duells, so they are reused in the first build step.
        if (isset($duells)) {
            foreach ($duells as $duell) {
                $this->add($duell);
            }
        }

        $this->build();
    }

    /**
     * @param \derhasi\upmkTournament\Contestant[]|ContestantCollection $contestant
     */
    public function addContestants(ContestantCollection $contestants) {
        $count = $this->contestantCollection->count();
        foreach ($contestants as $contestant) {
            $this->contestantCollection->add($contestant);
        }
        // We need to rebuild, when the number of contestants changed.
        if ($count != $this->contestantCollection->count()) {
            $this->build();
        }
    }

    /**
     * @param ContestantCollection $contestants
     */
    protected function build() {
        $combinations = CombinationFactory::create($this->contestantCollection->getIDs(), 2);

        foreach ($combinations as $key => $combination) {
            $duellContestants = new ContestantCollection();
            foreach($combination->getParts() as $contKey) {
                $duellContestants->add($this->contestantCollection[$contKey]);
            }

            $duell = new Duell($duellContestants);
            $this->add($duell);
        }
    }

    /**
     * @param ContestantCollection $contestants
     *
     * @return DuellCollection
     */
    public function getDuellsForContestants(ContestantCollection $contestants) {
        // Build a new set of duells, so we can extract the subset from the
        // current set.
        $duellCollection = new DuellCollection($contestants);

        $existingDuells = array();
        foreach ($duellCollection as $newDuell) {
            $existingDuells[] = $this->getItem($newDuell);
        }

        return new DuellCollection($contestants, $existingDuells);
    }



}
