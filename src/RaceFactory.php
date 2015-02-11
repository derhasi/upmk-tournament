<?php
/**
 * Created by PhpStorm.
 * User: derhasi
 * Date: 11.02.15
 * Time: 23:23
 */

namespace derhasi\upmkTournament;


class RaceFactory {

    public static function create($contestants, $groupSize) {
        $races = array();
        $combinations = CombinationFactory::create(array_keys($contestants), $groupSize);

        foreach ($combinations as $key => $combination) {

            $race_contestants = array();
            foreach($combination->getParts() as $contKey) {
                $race_contestants[] = $contestants[$contKey];
            }

            $race = new Race($race_contestants);
            $races[$race->getID()] = $race;
        }
        return $races;
    }

}
