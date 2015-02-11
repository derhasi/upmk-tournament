<?php
/**
 * Created by PhpStorm.
 * User: derhasi
 * Date: 11.02.15
 * Time: 20:55
 */

namespace derhasi\upmkTournament;


class Race
{

    /**
     * @var Contestant[]
     */
    protected $contestants;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var bool
     */
    protected $scheduled = false;

    /**
     * @var bool
     */
    protected $valid = true;

    public function __construct(array $contestants) {
        $this->contestants = $contestants;
    }

    public function isValid() {
        return $this->valid;
    }

    public function isScheduled() {
        return $this->scheduled;
    }

    public function schedule($name, $heat) {
        $this->scheduled = true;
        return $this;
    }

    public function invalidate() {
        $this->valid;
        return $this;
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


}
