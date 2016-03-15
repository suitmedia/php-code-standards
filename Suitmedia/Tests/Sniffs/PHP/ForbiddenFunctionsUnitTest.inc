<?php

namespace Suitmedia;

class ForbiddenFunctions
{
    public function test()
    {
        die();
        exit();
        dd();
        phpinfo();
        eval('$a = 5+1;');
        assert('$a = 5+1;');
        die;
        exit;
    }
}
