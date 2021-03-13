<?php

    function lang($phrase)
    {
        static $lang = array(
            //HOME PAGE
            'MESSAGE' => 'Welcome',
            'ADMIN' => 'Administrator'
        );
        return $lang[$phrase];
    }
    

?>