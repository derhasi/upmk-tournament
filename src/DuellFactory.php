<?php

namespace derhasi\upmkTournament;

class DuellFactory {

    /**
     * @param ContestantCollection $contestants
     *
     * @return Duell[]
     */
    public static function create($contestants) {
        $duells = array();

        $combinations = CombinationFactory::create($contestants->array_keys(), 2);

        foreach ($combinations as $key => $combination) {
            $duellContestants = new ContestantCollection();
            foreach($combination->getParts() as $contKey) {
                $duellContestants->add($contestants[$contKey]);
            }

            $duell = new Duell($duellContestants);

            $duells[$duell->getID()] = $duell;
        }

        return $duells;
    }

    /**
     * @param Contestant[] $contestants
     *
     * @return Duell[]
     */
    public static function createSingletons($contestants) {
        static $duells = array();

        $temp = static::create($contestants);
        $return = array();
        foreach ($temp as $duell) {
            $id = $duell->getID();
            if (!isset($duells[$id])) {
                $duells[$id] = $duell;
            }

            $return[$id] = $duells[$id];
        }
        return $return;
    }

}
