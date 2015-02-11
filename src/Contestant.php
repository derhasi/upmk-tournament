<?php

namespace derhasi\upmkTournament;

class Contestant
{

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $id;

    /**
     * @param string $name
     */
    public function __construct($name, $id)
    {
        $this->name = $name;
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getId() {
        return $this->id;
    }

}
