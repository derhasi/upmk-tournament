<?php

namespace derhasi\upmkTournament;

class CombinationFactory {

    /**
     * @param array $parts
     * @param int   $groupSize
     *
     * @return \derhasi\upmkTournament\Combination[string]
     */
    public static function create($parts, $groupSize) {
        $math = new \Math_Combinatorics();
        $combinations = $math->combinations($parts, $groupSize);
        $return = array();
        foreach ($combinations as $combination) {
            $combination = new Combination($combination);
            $return[$combination->getKey()] = $combination;
        }

        return $return;
    }
}
