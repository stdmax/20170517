<?php

$inputConf = [
	'count' => [
		'Input count (2 <= count <= 10^5)',
		function ($string, array $input) {
			if (0 == preg_match('/^([2-9]|[1-9]\d{1,4}|100000)$/', $number = trim($string))) {
				throw new \Exception('Invalid number format, expected: 2 <= count <= 10^5');
			}

			return $number;
		},
		function (array $input) {
			return rand(2, 10);
		},
	],
	'numbers' => [
		'Input numbers (1 <= number <= 10^5)',
		function ($string, array $input) {
			$numbers = array_map(function ($number) {
				return 0 != preg_match('/^([1-9]\d{0,4}|100000)$/', $number) ? $number : false;
			}, preg_split('/[^\d]+/', $string, -1, PREG_SPLIT_NO_EMPTY));

			if ($input['count'] != count(array_keys($numbers))) {
				throw new \Exception('Invalid numbers count, expected count: ' . $input['count']);
			} elseif (0 != count(array_keys($numbers, false, true))) {
				throw new \Exception('Invalid number format, expected: 1 <= number <= 10^5');
			}

			return $numbers;
		},
		function (array $input) {
			$numbers = [];
			for ($i = 0; $i < $input['count']; $i++) {
				array_push($numbers, rand(1, pow(10, 5)));
			}

			return implode(',', $numbers);
		},
	],
];

$input = [];
foreach ($inputConf as $name => $data) {
	list ($text, $parser, $default) = $data;
	do {
		echo $text . ':' . "\n" . '> ';
		if (0 == strlen($string = trim(fgets(STDIN)))) {
			$string = $default($input);
			echo '! generated: ' . $string . "\n";
		}
		try {
			$input[$name] = $parser($string, $input);
		} catch (\Exception $exception) {
			echo '! error: ' . $exception->getMessage() . "\n";
		}
	} while (!isset($input[$name]));
}

for ($prev = end($input['numbers']), $current = reset($input['numbers']), $spokes = 0, $position = -1; false !== $current; $prev = $current, $current = next($input['numbers'])) {
	if ($prev > $current) {
		$position = key($input['numbers']);
		++$spokes;
	}
}
if (1 != $spokes) {
	$iterations = -1;
} else {
	$iterations = 0 == $position ? 0 : $input['count'] - $position;
}
echo '! result: ' . $iterations . "\n";



