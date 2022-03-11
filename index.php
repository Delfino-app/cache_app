<?php

define("CACHE_JSON_EXPIRE",3600);
define("STORAGE_PATH",'cache_json');

require_once './cache.php';


$a = new cache();


//exit(json_encode($a->consumeAPI('https://api.dibi.com.br/v1/busca')));

$get = '';
$total_get = count($_GET);
$count = 0;

foreach ($_GET as $key => $value) {
    $count +=1;

    $inc = ($count < count($_GET)) ? '&' : '';
   
    $get = $get.$key."=".$value.$inc;
}

exit(json_encode($a->consumeAPI('https://api.dibi.com.br/v1/busca?'.$get)));
