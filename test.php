<?php
/**
 * Created by PhpStorm.
 * User: ZSL
 * Date: 2018/5/9
 * Time: 17:36
 */

$formation = array(120=>1,122=>2,121=>3);
$petData["formation_p"] = 0;
$petID = 130;
if (isset($formation[$petID]))
{
    echo 0;
    if ($petData["formation_p"] != $formation[$petID])
    {
        echo 1;
    }
}
else
{
    echo 3;
    if ($petData["formation_p"] != 0)
    {
        echo 2;
    }
}
die;
$pet['power'] = 1000;
$attack = $pet['power'];

function set(&$power){
    $power = 1;
}

set($attack );

var_dump($attack);