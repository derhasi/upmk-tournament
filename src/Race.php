<?php
/**
 * Created by PhpStorm.
 * User: derhasi
 * Date: 11.02.15
 * Time: 20:55
 */

namespace derhasi\upmkTournament;


class Race implements ItemInterface
{

    /**
     * @var ContestantCollection
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
     * @var Heat
     */
    protected $heat;

    /**
     * @var bool
     */
    protected $valid = true;

    /**
     * @var string
     */
    protected $name;

    public function __construct(ContestantCollection $contestants) {
        $this->contestants = $contestants;
    }

    public function isValid() {
        return $this->valid;
    }

    public function isScheduled() {
        return $this->scheduled;
    }

    public function schedule($name, Heat $heat) {
        $this->name = $name;
        $this->heat = $heat;
        $this->scheduled = true;
        return $this;
    }

    public function invalidate() {
        $this->valid = false;
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

    /**
     * @return ContestantCollection
     */
    public function getContestants() {
        return $this->contestants;
    }

    public function getName() {
        return $this->name;
    }

}
