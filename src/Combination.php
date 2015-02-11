<?php
/**
 * Created by PhpStorm.
 * User: derhasi
 * Date: 11.02.15
 * Time: 22:37
 */

namespace derhasi\upmkTournament;


class Combination {

    /**
     * @var array
     */
    protected $parts;

    /**
     * @var bool
     */
    protected $marked = false;

    /**
     * @var string
     */
    protected $key;

    public function __construct(array $combination) {
        sort($combination);
        $this->parts = $combination;
        $this->key = static::buildKey($combination);
    }

    /**
     * @param $combination
     */
    public function isMarked() {
        return $this->marked;
    }

    public function mark() {
        $this->marked = true;
    }

    public function getParts() {
        return $this->parts;
    }

    public function getKey() {
        return $this->key;
    }

    /**
     * @param array $combination
     *
     * @return string
     */
    public static function buildKey(array $combination) {
        return implode(':', $combination);
    }
}
