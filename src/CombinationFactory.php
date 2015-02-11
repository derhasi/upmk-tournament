<?php
/**
 * Created by PhpStorm.
 * User: derhasi
 * Date: 11.02.15
 * Time: 22:07
 */

namespace derhasi\upmkTournament;


class CombinationFactory {

    public static function createMultiple($contestantIDs, $groupSize) {
        return static::buildCombinations($contestantIDs, $groupSize);
    }

    /**
     * @param array $contestantIDs
     * @param int   $groupSize
     *
     * @return \derhasi\upmkTournament\Combination[string]
     */
    protected static function buildCombinations($contestantIDs, $groupSize) {
        $math = new \Math_Combinatorics();
        $combinations = $math->combinations($contestantIDs, $groupSize);
        $return = array();
        foreach ($combinations as $combination) {
            $combination = new Combination($combination);
            $return[$combination->getKey()] = $combination;
        }

        return $return;
    }
}
