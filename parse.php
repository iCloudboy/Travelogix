<?php
        require_once('class_parse.php');

        $parse = new Parse;


        $parse->Invoices();
        $parse->Head();
        $parse->Air();
        echo 'successfully updated mysql database';
?>
