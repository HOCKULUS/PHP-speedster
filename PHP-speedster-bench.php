<?php
session_start();
$action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
if(isset($action) && $action == "run"){
	$_SESSION['action'] = "run";
}
if(isset($action) && $action == "clear"){
	session_destroy();
	header("Location: ");
}
if($action != "run" || $action == "" | $action == NULL){
	$_SESSION['action'] = "stop";
}
if(isset($_SESSION['action']) && $_SESSION['action'] == "run"){
	$refresh = "<script>
					setTimeout(function(){
						location.reload();
					}, 2000);
				</script>";
				$startTime = hrtime(true);
$numbers = array();
for ($i = 0; $i < 1000000; $i++) {
    $numbers[] = rand(1, 100);
}
sort($numbers);
$results = array();
foreach ($numbers as $number) {
    $result = 1;
    for ($i = 1; $i <= $number; $i++) {
        $result *= $i;
    }
    $results[] = $result;
}
$filename = 'benchmark_results.txt';
$handle = fopen($filename, 'w');
foreach ($results as $result) {
    fwrite($handle, $result . "\n");
}
fclose($handle);

$endTime = hrtime(true);
$elapsedTime = ($endTime - $startTime) / 1e+9;

$_SESSION['benchmarks'][] = array(
    'date' => date('Y-m-d H:i:s'),
    'time' => $elapsedTime
);

$benchmarks = isset($_SESSION['benchmarks']) ? $_SESSION['benchmarks'] : array();

if (count($benchmarks) > 1) {
    $times = array();
    foreach ($benchmarks as $benchmark) {
        $times[] = $benchmark['time'];
    }
    $average = array_sum($times) / count($times);
    $min = min($times);
    $max = max($times);

    echo "Average time: " . $average . " seconds\n<br>";
    echo "Fastest time: " . $min . " seconds\n<br>";
    echo "Slowest time: " . $max . " seconds\n<br>";
}

$_SESSION['benchmarks'] = $benchmarks;

echo $refresh."Elapsed time: " . $elapsedTime . " seconds\n<br>";
}


?>
<form method="POST" action="">
  <button type="submit" name="action" value="run">Run</button>
  <button type="submit" name="action" value="stop">Stop</button>
  <button type="submit" name="action" value="clear">Clear</button>
</form>
<p>PHP-speedster Bench v1</p>
