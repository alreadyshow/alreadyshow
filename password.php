<?php
$a = 'qwertyuiopasdfghjklzxcvbnm';
$b = ',./;"[]!@#$%^&*()_+';
$c = 'QWERTYUIOPASDFGHJKLZXCVBNM';
$d = '0123456789';

//var_dump($a[rand(1,strlen($a))-1]);

$password = '';

for ($i=0; $i < 4; $i++) { 
	$password .= $a[rand(1,strlen($a))-1].$b[rand(1,strlen($b))-1].$c[rand(1,strlen($c))-1].$d[rand(1,strlen($d))-1];
}

echo $password;


