<?php

class AprintTest extends \PHPUnit_Framework_TestCase {


	protected static function getMethod($name) {
	  $class = new ReflectionClass('Aprint');
	  $method = $class->getMethod($name);
	  $method->setAccessible(true);
	  return $method;
	}	

	protected static function getProperty($name) {
	  $class = new ReflectionClass('Aprint');
	  $property = $class->getProperty($name);
	  $property->setAccessible(true);
	  return $property;
	}	


	/**
	* @covers \Aprint::__construct
	* @expectedException InvalidArgumentException
	**/

	public function testExceptionIsRaisedForInvalidConstructorArgument() {
		new \Aprint(null);
	}

 	/**
     * @covers \Aprint::__construct
     * 
     * 
     */
    public function testObjectCanBeConstructedForValidConstructorArgument()   {
        $ap = new Aprint(array(
							array(
								'Name' => 'Trixie',
								'Color' => 'Green',
								'Element' => 'Earth',
								'Likes' => 'Flowers'
							),
							array(
								'Name' => 'Tinkerbell',
								'Element' => 'Air',
								'Likes' => 'Singning',
								'Color' => 'Blue'
							), 
							array(
								'Element' => 'Water',
								'Likes' => 'Dancing',
								'Name' => 'Blum',
								'Color' => 'Pink'
							),
		));

        $this->assertInstanceOf('\Aprint', $ap);

        return $ap;
    }	

	/**
	* @covers \Aprint::findHeaderValues
	* @depends testObjectCanBeConstructedForValidConstructorArgument
	**/
    public function testFindHeaderValuesofTableInArray(Aprint $ap) {
		$find_header_values = self::getMethod('findHeaderValues');
    	$this->assertEquals(array('Name','Color','Element','Likes'), $find_header_values->invoke($ap));
    	$header = self::getProperty('header');
    	$this->assertEquals(array('Name','Color','Element','Likes'), $header->getValue($ap));
    }


	/**
	* @covers \Aprint::findLongest
	* @depends testObjectCanBeConstructedForValidConstructorArgument
	* @depends testFindHeaderValuesofTableInArray
	**/
    public function testFindLongestValueOfTableInArray(Aprint $ap) {
		$find_longest = self::getMethod('findLongest');
		$this->assertEquals('Tinkerbell', $find_longest->invoke($ap) );
    }

	/**
	* @covers \Aprint::calcColumnLength
	* @depends testObjectCanBeConstructedForValidConstructorArgument
	*
	**/
    public function testCalcColumnLength(Aprint $ap) {
    	$calc_column_length = self::getMethod('calcColumnLength');
    	$this->assertEquals(11, $calc_column_length->invoke($ap, 'Tinkerbell'));
    	$col_length = self::getProperty('col_length');
    	$this->assertEquals(11, $col_length->getValue($ap));
    }

	/**
	* @covers \Aprint::buildLine
	* @depends testObjectCanBeConstructedForValidConstructorArgument
	* @depends testFindHeaderValuesofTableInArray
	* @depends testCalcColumnLength
	**/
    public function testBuildLine(Aprint $ap) {
		$build_line = self::getMethod('buildLine');
		$this->assertEquals('
+-----------+-----------+-----------+-----------+', $build_line->invoke($ap) );
    }

	/**
	* @covers \Aprint::buildLine
	* @depends testObjectCanBeConstructedForValidConstructorArgument
	* @depends testCalcColumnLength
	**/
    public function testBuildRow(Aprint $ap) {
		$build_row = self::getMethod('buildRow');
		$this->assertEquals('
|One        |Two        |Three      |Four       |', $build_row->invoke($ap, array('One','Two','Three','Four') ) );
		$this->assertEquals('[0;32m
|Trixie     |Green      |Earth      |Flowers    |[0m', $build_row->invoke($ap, array('Trixie','Green','Earth','Flowers') ) );		
    }

	/**
	* @covers \Aprint::buildHeader
	* @depends testObjectCanBeConstructedForValidConstructorArgument
	* @uses \Aprint::buildRow
	* @uses \Aprint::buildLine
	**/
    public function testBuildHeader(Aprint $ap) {
		$build_header = self::getMethod('buildHeader');
		$this->assertEquals('
+-----------+-----------+-----------+-----------+
|Name       |Color      |Element    |Likes      |
+-----------+-----------+-----------+-----------+', $build_header->invoke($ap) );
    }


	/**
	* @covers \Aprint::arrangeRow
	* @depends testObjectCanBeConstructedForValidConstructorArgument
	* @depends testFindHeaderValuesofTableInArray
	**/
    public function testArrangeRow(Aprint $ap) {
    	$arrange_row = self::getMethod('arrangeRow');
    	$this->assertEquals(
    				array('Name' => 'Blum', 'Color' => 'Pink', 'Element' => 'Water', 'Likes' => 'Dancing'), 
    				$arrange_row->invoke($ap, array('Element' => 'Water','Likes' => 'Dancing','Name' => 'Blum','Color' => 'Pink') ) 
		);
    }




	/**
	* @covers \Aprint::ascii
	* @depends testObjectCanBeConstructedForValidConstructorArgument
	* @uses \Aprint::buildRow
	* @uses \Aprint::buildLine
	* @uses \Aprint::arrangeRow
	* @uses \Aprint::buildHeader
	**/
	public function testAscii(Aprint $ap) {
		$this->assertEquals('
+-----------+-----------+-----------+-----------+
|Name       |Color      |Element    |Likes      |
+-----------+-----------+-----------+-----------+[0;32m
|Trixie     |Green      |Earth      |Flowers    |[0m
+-----------+-----------+-----------+-----------+[0;34m
|Tinkerbell |Blue       |Air        |Singning   |[0m
+-----------+-----------+-----------+-----------+[0;35m
|Blum       |Pink       |Water      |Dancing    |[0m
+-----------+-----------+-----------+-----------+', $ap->ascii());
	}


}