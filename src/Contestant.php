<?php

namespace derhasi\upmkTournament;

class Contestant
{

    /**
     * @var string
     */
    public $name;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

}
