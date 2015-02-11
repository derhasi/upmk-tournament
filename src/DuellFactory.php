<?php

namespace derhasi\upmkTournament;

class DuellFactory {

    /**
     * @param Contestant[] $contestants
     *
     * @return Duell[]
     */
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
