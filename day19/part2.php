<?php
//our pattern says that for any number, n,
//we find the greatest power of 3 (p) where p <= n.
//then, we set the value at index p+1 (n0) = 1.
//we then increase the value by 1 until n0 == n
//if our count reaches the same value as our power of 3,
//then we start counting odd digits after that

//for example, if n = 20
//then p = 9
//so, 10 <- 1
// 11 <- 2
// ...
// 17 <- 8
// 18 <- 9
// 19 <- 11
// 20 <- 13



$num_elves = $argv[1];

//step 1, find closest power of 3 less than our number of elves
$log = intval(log($num_elves,3));
$pow1 = pow(3,$log);

$inc = 1;
$j = 0;
for($i=$pow1;$i<$num_elves;$i++){
	if($j == $pow1){
		$inc = 2;
	}
	$j += $inc;
	
}



echo "Elf {$j} has all the presents.".PHP_EOL;