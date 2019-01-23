<?PHP
	$conf = [
		['rate' => '0.30', 'totalNum' => '1500', 'cash' => 6],
		['rate' => '0.24', 'totalNum' => '1200', 'cash' => 8],
		['rate' => '0.20', 'totalNum' => '1000', 'cash' => 16],
		['rate' => '0.14', 'totalNum' => '700', 'cash' => 18], 
		['rate' => '0.08', 'totalNum' => '400', 'cash' => 68],
		['rate' => '0.04', 'totalNum' => '200', 'cash' => 168],
	];

    function getRandom(array $array, array $rate){
        $sum = 0;
        $left = 0;
        $right = 0;
        foreach($rate as $value){
            $sum+=$value*10;
        }
        
        $temp = rand(0,$sum);
        //echo 'temp=>'.$temp.PHP_EOL;
        foreach($rate as $key=>$value){
            $right+=$value*10;
            // echo 'left=>'.$left.PHP_EOL; 
            // echo 'right=>'.$right.PHP_EOL;
            // echo 'key=>'.$key.PHP_EOL;
            if($left<=$temp && $temp<$right){
                return $array[$key];
            }
            $left+=$value*10;
        }
    }
    $arr = array_column($conf, 'cash');
    $rate = array_column($conf, 'rate');
    $cashNum = array_column($conf, 'totalNum', 'cash');
    $cashRate = array_column($conf, 'rate', 'cash');
    // $arr = [6,8,16,18,68,168];
    // $rate = '0.3:0.24:0.2:0.14:0.08:0.04';
    
    function getRandRes($totalCash,$totalNum,$cashRate,$cashNum)
    {
    	$result = [];
    	$result['count'] = ['count'];
    	$realCash = 0;
    	for ($i=0; $i < $totalNum; $i++) { 

    		$rand = getRandom(array_keys($cashRate),array_values($cashRate));
    		$realCash += $rand;
    		if ($realCash>=$totalCash) {
    			return $result;
    		}
	    	foreach (array_keys($cashRate) as $value) {
	    		if ($rand == $value) {
	    			$result[$value][] = $value;
	    		}
	    	}
	    	//array_push($result, $rand);
	    	foreach ($cashRate as $key => $value) {
	    		if (isset($result[$key])) {
	    			if (count($result[$key])>=$cashNum[$key]) {
	    				unset($cashRate[$key]);
		    		}
		    		$result['count'][$key] = count($result[$key]);
	    		}
	    	}
    	}
    	return $result;
    }
    echo json_encode(getRandRes(100000,5000,$cashRate,$cashNum),320);

/*
    $a=$b=$c=$d=$e=$f=0;
    for($i=0;$i<1000;$i++){
        if(getRandom($arr,$rate)==6){
            $a++;
        }
        if(getRandom($arr,$rate)==8){
            $b++;
        }
        if(getRandom($arr,$rate)==16){
            $c++;
        }
        if(getRandom($arr,$rate)==18){
            $d++;
        }
        if(getRandom($arr,$rate)==68){
            $e++;
        }
        if(getRandom($arr,$rate)==168){
            $f++;
        }
    }
    echo $a.PHP_EOL;
    echo $b.PHP_EOL;
    echo $c.PHP_EOL;
    echo $d.PHP_EOL;
    echo $e.PHP_EOL;
    echo $f.PHP_EOL;

/*
    $array = array(0,1,2,3);
    $rate = '2:1:3:5';
    $a=$b=$c=$d=0;
    for($i=0;$i<1100;$i++){
        if(getRandom($array,$rate)==0){
            $a++;
        }
        if(getRandom($array,$rate)==1){
            $b++;
        }
        if(getRandom($array,$rate)==2){
            $c++;
        }
        if(getRandom($array,$rate)==3){
            $d++;
        }
    }
    echo $a;
    echo "\n";
    echo $b;
    echo "\n";
    echo $c;
    echo "\n";
    echo $d;
    echo "\n";
*/