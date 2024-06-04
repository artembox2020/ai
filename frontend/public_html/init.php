<?php

//var_dump($_REQUEST['domain']); die;

//header('Content-Type: text/plain;');

$initapi = file_get_contents($_REQUEST['domain'] . "/ajax/get-file?file=initapi.php");
file_put_contents("initapi.php", $initapi);
