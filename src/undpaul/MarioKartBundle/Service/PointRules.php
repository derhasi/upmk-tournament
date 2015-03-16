<?php

namespace undpaul\MarioKartBundle\Service;


class PointRules
{

    /**
     * @var array
     */
    protected $absolute;

    /**
     * @var
     */
    protected $relative;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $pointsAbsolute = [
          1 => 15,
          2 => 12,
          3 => 10,
          4 => 9,
          5 => 8,
          6 => 7,
          7 => 6,
          8 => 5,
          9 => 4,
          10 => 3,
          11 => 2,
          12 => 1,
        ];
        $pointsRelative = [
          1 => 4,
          2 => 3,
          3 => 2,
          4 => 1,
        ];

        $this->absolute = $pointsAbsolute;
        $this->relative = $pointsRelative;
    }

    public function getAbsolutePoint($position)
    {
        if (isset($this->absolute[$position])) {
            return $this->absolute[$position];
        }

        return 0;
    }

    public function getRelativePoint($relativePosition)
    {
        if (isset($this->relative[$relativePosition])) {
            return $this->relative[$relativePosition];
        }

        return 0;
    }


}