<?php

class Aprint {
	
	private $array;
	private $col_length;
	private $header;

	private static $foreground = array(
		'green' => '0;32',
		'blue' => '0;34',
		'pink' => '0;35', //purple
	);
 

	public static function fgColor($color, $string) {
		if (!isset(self::$foreground[$color]))  {
			throw new Exception('Foreground color is not defined');
		}
		return "\033[" . self::$foreground[$color] . "m" . $string . "\033[0m";
	}	


	public function __construct($array) {
		if (empty($array) or !is_array($array) or !is_array($array[0])) {
			throw new InvalidArgumentException('$array must be two dimensional array');
		}
		$this->array = $array;
		$this->findHeaderValues();
		$this->calcColumnLength( $this->findLongest() );
	}

	public function ascii() {
		$str = $this->buildHeader();
		foreach ($this->array as $row) {
			$str .= $this->buildRow( $this->arrangeRow( $row ) ) ;
		}
		return $str . $this->buildLine();
	}

	protected function arrangeRow($row) {
		$new = array();
		foreach ($this->header as $idx) {
			$new[$idx] = $row[$idx];
		}
		return $new;
	}

	protected function buildHeader() {
		return $this->buildLine() . $this->buildRow($this->header) . $this->buildLine();
	}

	protected function buildRow($values) {
		$str = "\r\n|";
		foreach ($values as $value) {
			$str .=  str_pad($value,  $this->col_length, ' ') . '|';
		}
		foreach (array_keys(self::$foreground) as $color) {
			if (strpos($str, ucfirst($color)) !== false) {
				$str = self::fgColor($color, $str);
			}
		}
		return  $str;
	}

	protected function buildLine() {
		$str = "\r\n+";
		for ($i = 0; $i < count($this->header); $i++) {
			$str .= str_pad('', $this->col_length, '-') . '+';
		}
		return $str;
	}

	protected function findLongest() {
		$list = $this->header;
		foreach ($this->array as  $sub) {
			$values = 	array_values($sub);
			$list = array_merge($list, $values);
		}
		usort($list, function($a, $b){
			return strlen($a) < strlen($b);
		});
		return $list[0];
	}

	protected function calcColumnLength($longest) {
		$this->col_length = strlen($longest) + 1;
		return $this->col_length;
	}

	protected function findHeaderValues() {
		$this->header = array_keys($this->array[0]);
		return $this->header;
	}

}
