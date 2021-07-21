<?php

namespace Yankovenko\utils;

require '../src/AddressParser.php';

$tests = file('address.txt');
$ok=0;
$total=0;
foreach($tests as $test) {

    $res = AddressParser::parse($test);
    if ($res) {
//        echo "\n".$test;
//        print_r(parseAddr::parse($test));
        $ok++;
    }
    $total++;
}

echo PHP_EOL.'Total: '.$total;
echo PHP_EOL.'Ok: '.$ok;
