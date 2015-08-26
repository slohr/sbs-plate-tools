import unittest
from sbstools import PlateMapUtil

class TestPlateMapUtil(unittest.TestCase):

	def setUp(self):
		self.plate = PlateMapUtil()

	def test_WellLookup96(self):
		res = self.plate.well_lookup_by_index(PlateMapUtil.PLATE_TYPE_96,12)
		self.assertEquals('A12',res)
		res = self.plate.well_lookup_by_index(PlateMapUtil.PLATE_TYPE_96,13)
		self.assertEquals('B01',res)
		res = self.plate.well_lookup_by_index(PlateMapUtil.PLATE_TYPE_96,24)
		self.assertEquals('B12',res)
		res = self.plate.well_lookup_by_index(PlateMapUtil.PLATE_TYPE_96,96)
		self.assertEquals('H12',res)
	def test_WellLookup384(self):
		res = self.plate.well_lookup_by_index(PlateMapUtil.PLATE_TYPE_384,1)
		self.assertEquals('A01',res)
		res = self.plate.well_lookup_by_index(PlateMapUtil.PLATE_TYPE_384,24)
		self.assertEquals('A24',res)
		res = self.plate.well_lookup_by_index(PlateMapUtil.PLATE_TYPE_384,25)
		self.assertEquals('B01',res)
		res = self.plate.well_lookup_by_index(PlateMapUtil.PLATE_TYPE_384,48)
		self.assertEquals('B24',res)
		res = self.plate.well_lookup_by_index(PlateMapUtil.PLATE_TYPE_384,49)
		self.assertEquals('C01',res)
		res = self.plate.well_lookup_by_index(PlateMapUtil.PLATE_TYPE_384,72)
		self.assertEquals('C24',res)
		res = self.plate.well_lookup_by_index(PlateMapUtil.PLATE_TYPE_384,384)
		self.assertEquals('P24',res)

	def test_WellLookupInputIndexLowerException96(self):
		self.assertRaises(IndexError,self.plate.well_lookup_by_index,PlateMapUtil.PLATE_TYPE_96,0)

	def test_WellLookupInputIndexUpperException96(self):
		self.assertRaises(IndexError,self.plate.well_lookup_by_index,PlateMapUtil.PLATE_TYPE_96,97)

	def test_WellLookupInputIndexLowerException384(self):
		self.assertRaises(IndexError,self.plate.well_lookup_by_index,PlateMapUtil.PLATE_TYPE_384,0)

	def test_WellLookupInputIndexUpperException384(self):
		self.assertRaises(IndexError,self.plate.well_lookup_by_index,PlateMapUtil.PLATE_TYPE_384,385)

	def test_LocationLookup96(self):
		res = self.plate.location_index_lookup_by_well(PlateMapUtil.PLATE_TYPE_96,'A12')
		self.assertEquals(12,res)
		res = self.plate.location_index_lookup_by_well(PlateMapUtil.PLATE_TYPE_96,'B01')
		self.assertEquals(13,res)
		res = self.plate.location_index_lookup_by_well(PlateMapUtil.PLATE_TYPE_96,'B12')
		self.assertEquals(24,res)

	def test_LocationLookup384(self):
		res = self.plate.location_index_lookup_by_well(PlateMapUtil.PLATE_TYPE_384,'B01')
		self.assertEquals(25,res)
		res = self.plate.location_index_lookup_by_well(PlateMapUtil.PLATE_TYPE_384,'B24')
		self.assertEquals(48,res)

	def test_PlateTypeException(self):
		self.assertRaises(ValueError,self.plate.well_lookup_by_index,'threeeighty4',42)
		self.assertRaises(ValueError,self.plate.location_index_lookup_by_well,'blue','B24')

	def test_RowLookupByIndex96(self):
		res = self.plate.row_number_lookup_by_index(PlateMapUtil.PLATE_TYPE_96,1)
		self.assertEquals(1,res)
		res = self.plate.row_number_lookup_by_index(PlateMapUtil.PLATE_TYPE_96,54)
		self.assertEquals(5,res)
		res = self.plate.row_number_lookup_by_index(PlateMapUtil.PLATE_TYPE_96,96)
		self.assertEquals(8,res)

	def test_RowLookupByIndex384(self):
		res = self.plate.row_number_lookup_by_index(PlateMapUtil.PLATE_TYPE_384,1)
		self.assertEquals(1,res)
		res = self.plate.row_number_lookup_by_index(PlateMapUtil.PLATE_TYPE_384,203)
		self.assertEquals(9,res)
		res = self.plate.row_number_lookup_by_index(PlateMapUtil.PLATE_TYPE_384,384)
		self.assertEquals(16,res)
	
	def test_TranslateRowIndexToLetter(self):
		res = self.plate.translate_row_index_to_letter(1)
		self.assertEquals('A',res)
		res = self.plate.translate_row_index_to_letter(5)
		self.assertEquals('E',res)
		res = self.plate.translate_row_index_to_letter(8)
		self.assertEquals('H',res)
		res = self.plate.translate_row_index_to_letter(16)
		self.assertEquals('P',res)

	def test_TranslateLetterToRowIndex(self):
		res = self.plate.translate_letter_to_row_index('A')
		self.assertEquals(1,res)
		res = self.plate.translate_letter_to_row_index('B')
		self.assertEquals(2,res)
		res = self.plate.translate_letter_to_row_index('H')
		self.assertEquals(8,res)
		res = self.plate.translate_letter_to_row_index('P')
		self.assertEquals(16,res)

	def test_TranslateLetterToRowIndexBoundaryException(self):
		self.assertRaises(IndexError,self.plate.translate_letter_to_row_index,'Q')

	def test_TranslateLetterToRowIndexDoubleInputException(self):
		self.assertRaises(ValueError,self.plate.translate_letter_to_row_index,'AA')

	def test_TranslateLetterToRowIndexNoInputException(self):
		self.assertRaises(ValueError,self.plate.translate_letter_to_row_index,'')

	def test_TranslateRowIndexToLetterBoundaryException(self):
		self.assertRaises(IndexError,self.plate.translate_row_index_to_letter,17)

	def test_TranslateRowIndexToLetterNullException(self):
		self.assertRaises(IndexError,self.plate.translate_row_index_to_letter,'')

	def test_ColumnNumberLookupByIndex96(self):
		res = self.plate.column_number_lookup_by_index(PlateMapUtil.PLATE_TYPE_96,1)
		self.assertEquals(1,res)
		res = self.plate.column_number_lookup_by_index(PlateMapUtil.PLATE_TYPE_96,58)
		self.assertEquals(10,res)
		res = self.plate.column_number_lookup_by_index(PlateMapUtil.PLATE_TYPE_96,96)
		self.assertEquals(12,res)

	def test_ColumnNumberLookupByIndex384(self):
		res = self.plate.column_number_lookup_by_index(PlateMapUtil.PLATE_TYPE_384,1)
		self.assertEquals(1,res)
		res = self.plate.column_number_lookup_by_index(PlateMapUtil.PLATE_TYPE_384,214)
		self.assertEquals(22,res)
		res = self.plate.column_number_lookup_by_index(PlateMapUtil.PLATE_TYPE_384,384)
		self.assertEquals(24,res)

	def test_Location96ToLocation384(self):
		res = self.plate.location_96_to_location_384(PlateMapUtil.QUADRANT_ONE,42)
		self.assertEquals(155,res)
		res = self.plate.location_96_to_location_384(PlateMapUtil.QUADRANT_TWO,42)
		self.assertEquals(156,res)
		res = self.plate.location_96_to_location_384(PlateMapUtil.QUADRANT_THREE,42)
		self.assertEquals(179,res)
		res = self.plate.location_96_to_location_384(PlateMapUtil.QUADRANT_FOUR,42)
		self.assertEquals(180,res)

	def test_Location96ToLocation384BadQuadrantException(self):
		self.assertRaises(ValueError,self.plate.location_96_to_location_384,'Q5',42)

	def test_Location384ToLocation96(self):
		res = self.plate.location_384_to_location_96(1)
		self.assertEquals(1,res)
		res = self.plate.location_384_to_location_96(155)
		self.assertEquals(42,res)
		res = self.plate.location_384_to_location_96(156)
		self.assertEquals(42,res)
		res = self.plate.location_384_to_location_96(179)
		self.assertEquals(42,res)
		res = self.plate.location_384_to_location_96(180)
		self.assertEquals(42,res)
		res = self.plate.location_384_to_location_96(384)
		self.assertEquals(96,res)

	def test_Location384ToQuadrant(self):
		res = self.plate.location_384_to_quadrant(1)
		self.assertEquals(PlateMapUtil.QUADRANT_ONE,res)
		res = self.plate.location_384_to_quadrant(5)
		self.assertEquals(PlateMapUtil.QUADRANT_ONE,res)
		res = self.plate.location_384_to_quadrant(25)
		self.assertEquals(PlateMapUtil.QUADRANT_THREE,res)
		res = self.plate.location_384_to_quadrant(241)
		self.assertEquals(PlateMapUtil.QUADRANT_ONE,res)
		res = self.plate.location_384_to_quadrant(155)
		self.assertEquals(PlateMapUtil.QUADRANT_ONE,res)
		res = self.plate.location_384_to_quadrant(156)
		self.assertEquals(PlateMapUtil.QUADRANT_TWO,res)
		res = self.plate.location_384_to_quadrant(179)
		self.assertEquals(PlateMapUtil.QUADRANT_THREE,res)
		res = self.plate.location_384_to_quadrant(180)
		self.assertEquals(PlateMapUtil.QUADRANT_FOUR,res)
		res = self.plate.location_384_to_quadrant(384)
		self.assertEquals(PlateMapUtil.QUADRANT_FOUR,res)

if __name__ == '__main__':
		unittest.main()
