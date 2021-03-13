<?php

    function lang($phrase)
    {
        static $lang = array(
            'MESSAGE' => 'اهلا',
            'ADMIN' => 'ادمين'
        );
        return $lang[$phrase];
    }
    

?>