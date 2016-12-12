<?php

$input = file_get_contents(__DIR__."/input.txt");

preg_match_all('/\(\d+x\d+\)/',$input,$m)

echo "Total Length: ".strlen($output).PHP_EOL;