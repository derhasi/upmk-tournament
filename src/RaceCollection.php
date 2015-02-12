<?php

namespace derhasi\upmkTournament;

class RaceCollection extends CollectionBase {

    /**
     * @var \derhasi\upmkTournament\ContestantCollection
     */
    protected $contestants;

    /**
     * @var int
     */
    protected $raceSize;

    public function __construct(ContestantCollection $contestants, $raceSize) {
        $this->contestants = $contestants;
        $this->raceSize = $raceSize;
        $this->build();
    }

    protected function build() {
        $combinations = CombinationFactory::create($this->contestants->getIDs(), $this->raceSize);

        foreach ($combinations as $key => $combination) {

            $race_contestants = new ContestantCollection();
            foreach($combination->getParts() as $contKey) {
                $race_contestants->add($this->contestants[$contKey]);
            }
            $this->add(new Race($race_contestants));
        }
    }

}
