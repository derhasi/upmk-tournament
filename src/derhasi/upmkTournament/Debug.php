<?php

namespace derhasi\upmkTournament;

class Debug {
    public static function dump($val) {
        $args = func_get_args();
        array_shift($args);

        print '================= ';
        print call_user_func_array('sprintf', $args);
        print ' =================';
        var_dump($val);
    }
}
