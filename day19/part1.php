<?php

//josephus problem
//answers are a series of odd numbers that start back at 1 every time
//the index is a power of 2

$num_elves = $argv[1];

//step 1, find closest power of 2 less than our number of elves
$pow = pow(2,intval(log($num_elves,2)));

//step 2, find the difference between number of elves an power of 2
$diff = $num_elves - $pow;

//step 3, find the final elf
$elf = $diff*2+1;

echo "Elf {$elf} has all the presents.".PHP_EOL;





