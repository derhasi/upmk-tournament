<?php

namespace derhasi\upmkTournament;

class DuellFactory {

    public static function create($contestants) {
        $duells = array();
        $combinations = CombinationFactory::create(array_keys($contestants), 2);

        foreach ($combinations as $key => $combination) {

            $duell_contestants = array();
            foreach($combination->getParts() as $contKey) {
                $duell_contestants[] = $contestants[$contKey];
            }

            $duell = new Duell($duell_contestants);
            $duells[$duell->getID()] = $duell;
        }
        return $duells;
    }

}
