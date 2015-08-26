<?
class PlateUtil {

	const ASCII_CHAR_A_VALUE = 65;

	const PLATE_TYPE_96 = '96';
	const PLATE_TYPE_384 = '384';

	const ROW_COUNT_96 = 8;
	const COLUMN_COUNT_96 = 12;
	const ROW_COUNT_384 = 16;
	const COLUMN_COUNT_384 = 24;

	const MAX_INDEX_96 = 96;
	const MAX_INDEX_384 = 384;

	const GLOBAL_MAX_ROW_INDEX = 16; 
	const GLOBAL_MAX_LOCATION_INDEX = 384;

	// Quadrant Layout. 
	// This is just an arbitrary convention.
	//      1       0
	//   -----------------
	//   |       |       |
	//   |  Q1   |  Q2   |
	// 1 |  11   |  10   |
	//   |       |       |
	//   -----------------
	//   |       |       |
	//   |  Q3   |  Q4   |
	// 0 |  01   |  00   |
	//   |       |       |
	//   -----------------
	//QUADRANT_ONE = Q1, etc.
	const QUADRANT_ONE = '11';
	const QUADRANT_TWO = '10';
	const QUADRANT_THREE = '01';
	const QUADRANT_FOUR = '00';
	
	function wellLookupByIndex($plateType,$locationIndex){
		$columnCount;
		$maxIndex;
		switch($plateType){
			case PlateUtil::PLATE_TYPE_96:
				$maxIndex = PlateUtil::MAX_INDEX_96;
				$columnCount = PlateUtil::COLUMN_COUNT_96;
			break;
			case PlateUtil::PLATE_TYPE_384:
				$maxIndex = PlateUtil::MAX_INDEX_384;
				$columnCount = PlateUtil::COLUMN_COUNT_384;
			break;
			default:
				throw new InvalidArgumentException("Acceptable plate types: ".PlateUtil::PLATE_TYPE_96." or ".PlateUtil::PLATE_TYPE_384);
		}

		if($locationIndex < 1 || $locationIndex > $maxIndex) {
			throw new InvalidArgumentException("Index out of range for plate type: ".$plateType);
		}
		$row = floor(($locationIndex-1)/$columnCount);
		$column = $locationIndex - ($row * $columnCount);
		return chr(PlateUtil::ASCII_CHAR_A_VALUE + $row).sprintf('%02d',$column);
	}

	function locationIndexLookupByWell($plateType, $well) {
		$well = strtoupper($well);
		$row = substr($well,0,1);
		$column = substr($well,1);
		switch($plateType){
			case PlateUtil::PLATE_TYPE_96:
				return (ord($row)-PlateUtil::ASCII_CHAR_A_VALUE)*PlateUtil::COLUMN_COUNT_96 + $column;
			break;
			case PlateUtil::PLATE_TYPE_384:
				return (ord($row)-PlateUtil::ASCII_CHAR_A_VALUE)*PlateUtil::COLUMN_COUNT_384 + $column;
			break;
			default:
				throw new InvalidArgumentException("Acceptable plate types: ".PlateUtil::PLATE_TYPE_96." or ".PlateUtil::PLATE_TYPE_384);
		}
	}

	function rowNumberLookupByIndex($plateType,$locationIndex){
		$maxIndex;
		$columnCount;
		switch($plateType){
			case PlateUtil::PLATE_TYPE_96:
				$maxIndex = PlateUtil::MAX_INDEX_96;
				$columnCount = PlateUtil::COLUMN_COUNT_96;
			break;
			case PlateUtil::PLATE_TYPE_384:
				$maxIndex = PlateUtil::MAX_INDEX_384;
				$columnCount = PlateUtil::COLUMN_COUNT_384;
			break;
			default:
				throw new InvalidArgumentException("Acceptable plate types: ".PlateUtil::PLATE_TYPE_96." or ".PlateUtil::PLATE_TYPE_384);
		}

		if($locationIndex < 1 || $locationIndex > $maxIndex) {
			throw new InvalidArgumentException("Acceptable index range for plate type: ".$plateType." is 1 to ".$maxIndex);
		}

		//we add a magical "1" to make it "1" indexed. The calculation will return 0-11 for a 96 well plate so we adjust to match what's actually
		//on the plate 1-12
		return floor(($locationIndex-1)/$columnCount) + 1;
	}

	function columnNumberLookupByIndex($plateType,$locationIndex){
		$columnCount;
		switch($plateType){
			case PlateUtil::PLATE_TYPE_96:
				$columnCount = PlateUtil::COLUMN_COUNT_96;
			break;
			case PlateUtil::PLATE_TYPE_384:
				$columnCount = PlateUtil::COLUMN_COUNT_384;
			break;
			default:
				throw new InvalidArgumentException("Acceptable plate types: ".PlateUtil::PLATE_TYPE_96." or ".PlateUtil::PLATE_TYPE_384);
		}
		$row = $this->rowNumberLookupByIndex($plateType,$locationIndex);
		return $locationIndex - (($row-1) * $columnCount);
	}


	function translateRowIndexToLetter($rowIndex) {
		if(empty($rowIndex) || $rowIndex < 1 || $rowIndex > PlateUtil::GLOBAL_MAX_ROW_INDEX) {
			throw new InvalidArgumentException("Index supplied does not map to any plate map configured: ".$rowIndex);
		}
		//We subtract a magical "1" so that the ASCII character
		//math (0 based) works out.
		//Row "1" is the "A" row and since A=65 in ASCII value space we need
		//to subtract one for each calculation.
		return chr(($rowIndex-1)+PlateUtil::ASCII_CHAR_A_VALUE);
	}

	function translateLetterToRowIndex($letter) {
		if(strlen($letter) == 0 || strlen($letter)>1) {
			throw new InvalidArgumentException("Single letter required as input.");
		}
		//We add a magical "1" so that the ASCII character
		//math (0 based) works out.
		//Row "1" is the "A" row and since A=65 and A-A = 65-65 = 0 but
		//it really should be "1" (in Plate coordinate space) we need
		//to add one for each calculation.
		$rowIndex = (ord($letter) - PlateUtil::ASCII_CHAR_A_VALUE) + 1;
		if($rowIndex > PlateUtil::GLOBAL_MAX_ROW_INDEX) {
			throw new InvalidArgumentException("Letter supplied does not map to any plate map configured: ".$letter);
		}
		return $rowIndex;
	}

	function location96ToLocation384($quadrant,$locationIndex) {
	
		$row96 = $this->rowNumberLookupByIndex(PlateUtil::PLATE_TYPE_96,$locationIndex);	
		$column96 = $this->columnNumberLookupByIndex(PlateUtil::PLATE_TYPE_96,$locationIndex);	

		$quadrantOneLocationIndex = ((($row96 * 2) - 2) * PlateUtil::COLUMN_COUNT_384) + (($column96 * 2)-1);

		switch($quadrant){
			case PlateUtil::QUADRANT_ONE:
				return $quadrantOneLocationIndex;
			break;
			case PlateUtil::QUADRANT_TWO:
				return $quadrantOneLocationIndex + 1;
			break;
			case PlateUtil::QUADRANT_THREE:
				return $quadrantOneLocationIndex + PlateUtil::COLUMN_COUNT_384;
			break;
			case PlateUtil::QUADRANT_FOUR:
				return $quadrantOneLocationIndex + PlateUtil::COLUMN_COUNT_384 + 1;
			default:
				throw new InvalidArgumentException("Bad quadrant $quadrant");
			break;
		}
	}

	function location384ToLocation96($locationIndex) {
		$row384 = $this->rowNumberLookupByIndex(PlateUtil::PLATE_TYPE_384,$locationIndex); 
		$column384 =  $this->columnNumberLookupByIndex(PlateUtil::PLATE_TYPE_384,$locationIndex); 
		
		$rawRow96 = $row384/2;
		$rawColumn96 = $column384/2;

		$row96 = ceil($rawRow96);
		$column96 = ceil($rawColumn96);
		
		return (($row96 * PlateUtil::COLUMN_COUNT_96)- PlateUtil::COLUMN_COUNT_96 ) + $column96;
	}

	function location384ToQuadrant($locationIndex) {
		$row384 = $this->rowNumberLookupByIndex(PlateUtil::PLATE_TYPE_384,$locationIndex); 
		$column384 =  $this->columnNumberLookupByIndex(PlateUtil::PLATE_TYPE_384,$locationIndex); 
		
		$quadrant = $this->quadrantLookup($row384,$column384);

		return $quadrant;
	}

	function quadrantLookup($row,$column) {
		$rowBin = $row%2;	
		$columnBin = $column%2;	
		return ''.$rowBin.$columnBin.'';
	}
}
?>
