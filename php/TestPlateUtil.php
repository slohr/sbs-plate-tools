<?
include('./PlateUtil.php');

class TestPlateUtil extends PHPUnit_Framework_TestCase {
	protected $plate;

	public function setUp() {
		$this->plate = new PlateUtil();
	}

	public function testWellLookup96() {
		$res = $this->plate->wellLookupByIndex(PlateUtil::PLATE_TYPE_96,12);
		$this->assertEquals('A12',$res);
		$res = $this->plate->wellLookupByIndex(PlateUtil::PLATE_TYPE_96,13);
		$this->assertEquals('B01',$res);
		$res = $this->plate->wellLookupByIndex(PlateUtil::PLATE_TYPE_96,24);
		$this->assertEquals('B12',$res);
		$res = $this->plate->wellLookupByIndex(PlateUtil::PLATE_TYPE_96,96);
		$this->assertEquals('H12',$res);
	}

	public function testWellLookup384() {
		$res = $this->plate->wellLookupByIndex(PlateUtil::PLATE_TYPE_384,1);
		$this->assertEquals('A01',$res);
		$res = $this->plate->wellLookupByIndex(PlateUtil::PLATE_TYPE_384,24);
		$this->assertEquals('A24',$res);
		$res = $this->plate->wellLookupByIndex(PlateUtil::PLATE_TYPE_384,25);
		$this->assertEquals('B01',$res);
		$res = $this->plate->wellLookupByIndex(PlateUtil::PLATE_TYPE_384,48);
		$this->assertEquals('B24',$res);
		$res = $this->plate->wellLookupByIndex(PlateUtil::PLATE_TYPE_384,49);
		$this->assertEquals('C01',$res);
		$res = $this->plate->wellLookupByIndex(PlateUtil::PLATE_TYPE_384,72);
		$this->assertEquals('C24',$res);
		$res = $this->plate->wellLookupByIndex(PlateUtil::PLATE_TYPE_384,384);
		$this->assertEquals('P24',$res);
	}

	/**
	* @expectedException	InvalidArgumentException
	*/
	public function testWellLookupInputIndexLowerException96() {
		$res = $this->plate->wellLookupByIndex(PlateUtil::PLATE_TYPE_96,0);
	}
	/**
	* @expectedException	InvalidArgumentException
	*/
	public function testWellLookupInputIndexUpperException96() {
		$res = $this->plate->wellLookupByIndex(PlateUtil::PLATE_TYPE_96,97);
	}

	/**
	* @expectedException	InvalidArgumentException
	*/
	public function testWellLookupInputIndexLowerException384() {
		$res = $this->plate->wellLookupByIndex(PlateUtil::PLATE_TYPE_384,0);
	}
	/**
	* @expectedException	InvalidArgumentException
	*/
	public function testWellLookupInputIndexUpperException384() {
		$res = $this->plate->wellLookupByIndex(PlateUtil::PLATE_TYPE_384,385);
	}

	public function testLocationLookup96() {
		$res = $this->plate->locationIndexLookupByWell(PlateUtil::PLATE_TYPE_96,'A12');
		$this->assertEquals(12,$res);
		$res = $this->plate->locationIndexLookupByWell(PlateUtil::PLATE_TYPE_96,'B01');
		$this->assertEquals(13,$res);
		$res = $this->plate->locationIndexLookupByWell(PlateUtil::PLATE_TYPE_96,'B12');
		$this->assertEquals(24,$res);
	}

	public function testLocationLookup384() {
		$res = $this->plate->locationIndexLookupByWell(PlateUtil::PLATE_TYPE_384,'B01');
		$this->assertEquals(25,$res);
		$res = $this->plate->locationIndexLookupByWell(PlateUtil::PLATE_TYPE_384,'B24');
		$this->assertEquals(48,$res);
	}

	/**
	* @expectedException	InvalidArgumentException
	*/
	public function testPlateTypeException() {
		$res = $this->plate->wellLookupByIndex('threeeighty4',42);
		$res = $this->plate->locationIndexLookupByWell('blue','B24');
	}

	public function testRowLookupByIndex96() {
		$res = $this->plate->rowNumberLookupByIndex(PlateUtil::PLATE_TYPE_96,1);
		$this->assertEquals(1,$res);
		$res = $this->plate->rowNumberLookupByIndex(PlateUtil::PLATE_TYPE_96,54);
		$this->assertEquals(5,$res);
		$res = $this->plate->rowNumberLookupByIndex(PlateUtil::PLATE_TYPE_96,96);
		$this->assertEquals(8,$res);
	}

	public function testRowLookupByIndex384() {
		$res = $this->plate->rowNumberLookupByIndex(PlateUtil::PLATE_TYPE_384,1);
		$this->assertEquals(1,$res);
		$res = $this->plate->rowNumberLookupByIndex(PlateUtil::PLATE_TYPE_384,203);
		$this->assertEquals(9,$res);
		$res = $this->plate->rowNumberLookupByIndex(PlateUtil::PLATE_TYPE_384,384);
		$this->assertEquals(16,$res);
	}
	
	public function testTranslateRowIndexToLetter() {
		$res = $this->plate->translateRowIndexToLetter(1);
		$this->assertEquals('A',$res);
		$res = $this->plate->translateRowIndexToLetter(5);
		$this->assertEquals('E',$res);
		$res = $this->plate->translateRowIndexToLetter(8);
		$this->assertEquals('H',$res);
		$res = $this->plate->translateRowIndexToLetter(16);
		$this->assertEquals('P',$res);
	}

	public function testTranslateLetterToRowIndex() {
		$res = $this->plate->translateLetterToRowIndex('A');
		$this->assertEquals(1,$res);
		$res = $this->plate->translateLetterToRowIndex('B');
		$this->assertEquals(2,$res);
		$res = $this->plate->translateLetterToRowIndex('H');
		$this->assertEquals(8,$res);
		$res = $this->plate->translateLetterToRowIndex('P');
		$this->assertEquals(16,$res);
	}

	/**
	* @expectedException	InvalidArgumentException
	*/
	public function testTranslateLetterToRowIndexBoundaryException() {
		$res = $this->plate->translateLetterToRowIndex('Q');
	}

	/**
	* @expectedException	InvalidArgumentException
	*/
	public function testTranslateLetterToRowIndexDoubleInputException() {
		$res = $this->plate->translateLetterToRowIndex('AA');
	}

	/**
	* @expectedException	InvalidArgumentException
	*/
	public function testTranslateLetterToRowIndexNoInputException() {
		$res = $this->plate->translateLetterToRowIndex('');
	}

	/**
	* @expectedException	InvalidArgumentException
	*/
	public function testTranslateRowIndexToLetterBoundaryException() {
		$res = $this->plate->translateRowIndexToLetter(17);
	}

	/**
	* @expectedException	InvalidArgumentException
	*/
	public function testTranslateRowIndexToLetterNullException() {
		$res = $this->plate->translateRowIndexToLetter('');
	}

	public function testColumnNumberLookupByIndex96() {
		$res = $this->plate->columnNumberLookupByIndex(PlateUtil::PLATE_TYPE_96,1);
		$this->assertEquals(1,$res);
		$res = $this->plate->columnNumberLookupByIndex(PlateUtil::PLATE_TYPE_96,58);
		$this->assertEquals(10,$res);
		$res = $this->plate->columnNumberLookupByIndex(PlateUtil::PLATE_TYPE_96,96);
		$this->assertEquals(12,$res);
	}

	public function testColumnNumberLookupByIndex384() {
		$res = $this->plate->columnNumberLookupByIndex(PlateUtil::PLATE_TYPE_384,1);
		$this->assertEquals(1,$res);
		$res = $this->plate->columnNumberLookupByIndex(PlateUtil::PLATE_TYPE_384,214);
		$this->assertEquals(22,$res);
		$res = $this->plate->columnNumberLookupByIndex(PlateUtil::PLATE_TYPE_384,384);
		$this->assertEquals(24,$res);
	}

	public function testLocation96ToLocation384() {
		$res = $this->plate->location96ToLocation384(PlateUtil::QUADRANT_ONE,42);
		$this->assertEquals(155,$res);
		$res = $this->plate->location96ToLocation384(PlateUtil::QUADRANT_TWO,42);
		$this->assertEquals(156,$res);
		$res = $this->plate->location96ToLocation384(PlateUtil::QUADRANT_THREE,42);
		$this->assertEquals(179,$res);
		$res = $this->plate->location96ToLocation384(PlateUtil::QUADRANT_FOUR,42);
		$this->assertEquals(180,$res);
	}

	/**
	* @expectedException	InvalidArgumentException
	*/
	public function testLocation96ToLocation384BadQuadrant() {
		$res = $this->plate->location96ToLocation384('Q5',42);
	}

	public function testLocation384ToLocation96() {
		$res = $this->plate->location384ToLocation96(1);
		$this->assertEquals(1,$res);
		$res = $this->plate->location384ToLocation96(155);
		$this->assertEquals(42,$res);
		$res = $this->plate->location384ToLocation96(156);
		$this->assertEquals(42,$res);
		$res = $this->plate->location384ToLocation96(179);
		$this->assertEquals(42,$res);
		$res = $this->plate->location384ToLocation96(180);
		$this->assertEquals(42,$res);
		$res = $this->plate->location384ToLocation96(384);
		$this->assertEquals(96,$res);
	}

	public function testLocation384ToQuadrant() {
		$res = $this->plate->location384ToQuadrant(1);
		$this->assertEquals(PlateUtil::QUADRANT_ONE,$res);
		$res = $this->plate->location384ToQuadrant(5);
		$this->assertEquals(PlateUtil::QUADRANT_ONE,$res);
		$res = $this->plate->location384ToQuadrant(25);
		$this->assertEquals(PlateUtil::QUADRANT_THREE,$res);
		$res = $this->plate->location384ToQuadrant(241);
		$this->assertEquals(PlateUtil::QUADRANT_ONE,$res);
		$res = $this->plate->location384ToQuadrant(155);
		$this->assertEquals(PlateUtil::QUADRANT_ONE,$res);
		$res = $this->plate->location384ToQuadrant(156);
		$this->assertEquals(PlateUtil::QUADRANT_TWO,$res);
		$res = $this->plate->location384ToQuadrant(179);
		$this->assertEquals(PlateUtil::QUADRANT_THREE,$res);
		$res = $this->plate->location384ToQuadrant(180);
		$this->assertEquals(PlateUtil::QUADRANT_FOUR,$res);
		$res = $this->plate->location384ToQuadrant(384);
		$this->assertEquals(PlateUtil::QUADRANT_FOUR,$res);
	}
}
?>
