import math

class PlateMapUtil:

	ASCII_CHAR_A_VALUE = 65;

	PLATE_TYPE_96 = '96';
	PLATE_TYPE_384 = '384';

	ROW_COUNT_96 = 8;
	COLUMN_COUNT_96 = 12;
	ROW_COUNT_384 = 16;
	COLUMN_COUNT_384 = 24;

	MAX_INDEX_96 = 96;
	MAX_INDEX_384 = 384;

	GLOBAL_MAX_ROW_INDEX = 16;
	GLOBAL_MAX_LOCATION_INDEX = 384;

        # Quadrant Layout.
        # This is just an arbitrary convention.
        #      1       0
        #   ########-
        #   |       |       |
        #   |  Q1   |  Q2   |
        # 1 |  11   |  10   |
        #   |       |       |
        #   ########-
        #   |       |       |
        #   |  Q3   |  Q4   |
        # 0 |  01   |  00   |
        #   |       |       |
        #   ########-
        # QUADRANT_ONE = Q1, etc.

        QUADRANT_ONE = '11';
        QUADRANT_TWO = '10';
        QUADRANT_THREE = '01';
        QUADRANT_FOUR = '00';

	def well_lookup_by_index(self,plate_type,location_index):
		column_count = None
		max_index = None
		if plate_type == PlateMapUtil.PLATE_TYPE_96:
			max_index = PlateMapUtil.MAX_INDEX_96
			column_count = PlateMapUtil.COLUMN_COUNT_96
		elif plate_type == PlateMapUtil.PLATE_TYPE_384:
                                max_index = PlateMapUtil.MAX_INDEX_384
                                column_count = PlateMapUtil.COLUMN_COUNT_384
		else:
			raise ValueError("Acceptable plate types: {0} or {1}".format(PlateMapUtil.PLATE_TYPE_96,PlateMapUtil.PLATE_TYPE_384))

                if location_index < 1 or location_index > max_index:
                        raise IndexError("Index out of range for plate type: {0}".format(plate_type))

                row = math.floor((location_index-1)/column_count)
                column = int(location_index - (row * column_count))
                letter = chr(int(PlateMapUtil.ASCII_CHAR_A_VALUE + row))
                return "{0}{1:02d}".format(letter,column)

	def location_index_lookup_by_well(self,plate_type, well):
                well = well.upper()
                row = well[:1]
                column = int(well[1:])
                if plate_type == PlateMapUtil.PLATE_TYPE_96:
			return (ord(row)-PlateMapUtil.ASCII_CHAR_A_VALUE)*PlateMapUtil.COLUMN_COUNT_96 + column
		elif plate_type == PlateMapUtil.PLATE_TYPE_384:
			return (ord(row)-PlateMapUtil.ASCII_CHAR_A_VALUE)*PlateMapUtil.COLUMN_COUNT_384 + column
		else:
			raise ValueError("Acceptable plate types: {0} or {1}".format(PlateMapUtil.PLATE_TYPE_96,PlateMapUtil.PLATE_TYPE_384))


	def row_number_lookup_by_index(self,plate_type,location_index):
		column_count = None
		max_index = None
		if plate_type == PlateMapUtil.PLATE_TYPE_96:
			max_index = PlateMapUtil.MAX_INDEX_96
			column_count = PlateMapUtil.COLUMN_COUNT_96
		elif plate_type == PlateMapUtil.PLATE_TYPE_384:
			max_index = PlateMapUtil.MAX_INDEX_384
			column_count = PlateMapUtil.COLUMN_COUNT_384
		else:
			raise ValueError("Acceptable plate types: {0} or {1}".format(PlateMapUtil.PLATE_TYPE_96,PlateMapUtil.PLATE_TYPE_384))

                if location_index < 1 or location_index > max_index:
                        raise IndexError("Index out of range for plate type: {0}".format(plate_type))


                #we add a magical "1" to make it "1" indexed. The calculation will return 0-11 for a 96 well plate so we adjust to match what's actually
                #on the plate 1-12
                return int(math.floor((location_index-1)/column_count)) + 1


	def column_number_lookup_by_index(self,plate_type,location_index):
		column_count = None
		if plate_type == PlateMapUtil.PLATE_TYPE_96:
			column_count = PlateMapUtil.COLUMN_COUNT_96
		elif plate_type == PlateMapUtil.PLATE_TYPE_384:
			column_count = PlateMapUtil.COLUMN_COUNT_384
		else:
			raise ValueError("Acceptable plate types: {0} or {1}".format(PlateMapUtil.PLATE_TYPE_96,PlateMapUtil.PLATE_TYPE_384))

		row = self.row_number_lookup_by_index(plate_type,location_index)
                return location_index - ((row-1) * column_count)

	def translate_row_index_to_letter(self,row_index):
                if not row_index or row_index < 1 or row_index > PlateMapUtil.GLOBAL_MAX_ROW_INDEX:
			raise IndexError("Index supplied does not map to any plate map configured: {0}".format(row_index))

		#We subtract a magical "1" so that the ASCII character
		#math (0 based) works out.
		#Row "1" is the "A" row and since A=65 in ASCII value space we need
		#to subtract one for each calculation.
                return chr((row_index-1)+PlateMapUtil.ASCII_CHAR_A_VALUE)

	def translate_letter_to_row_index(self,letter):
                if not letter or len(letter) == 0 or len(letter)>1:
			raise ValueError("Letter is required: {0}".format(letter))

		row_index = (ord(letter)-PlateMapUtil.ASCII_CHAR_A_VALUE)+1
		if row_index > PlateMapUtil.GLOBAL_MAX_ROW_INDEX:
			raise IndexError("Letter supplied does not map to any plate map configured: {0}".format(letter));

                return row_index


	def location_96_to_location_384(self,quadrant,location_index):

                row96 = self.row_number_lookup_by_index(PlateMapUtil.PLATE_TYPE_96,location_index)
                column96 = self.column_number_lookup_by_index(PlateMapUtil.PLATE_TYPE_96,location_index)

                quadrant_one_location_index = (((row96 * 2) - 2) * PlateMapUtil.COLUMN_COUNT_384) + ((column96 * 2)-1)

                if quadrant == PlateMapUtil.QUADRANT_ONE:
                                return quadrant_one_location_index
		elif quadrant == PlateMapUtil.QUADRANT_TWO:
                                return quadrant_one_location_index + 1
		elif quadrant == PlateMapUtil.QUADRANT_THREE:
                                return quadrant_one_location_index + PlateMapUtil.COLUMN_COUNT_384
		elif quadrant == PlateMapUtil.QUADRANT_FOUR:
                                return quadrant_one_location_index + PlateMapUtil.COLUMN_COUNT_384 + 1
		else:
			raise ValueError("Bad quadrant {0}".format(quadrant))

	def location_384_to_location_96(self,location_index):
                row384 = self.row_number_lookup_by_index(PlateMapUtil.PLATE_TYPE_384,location_index)
                column384 =  self.column_number_lookup_by_index(PlateMapUtil.PLATE_TYPE_384,location_index)

                rawRow96 = row384/2.0
                rawColumn96 = column384/2.0

                row96 = math.ceil(rawRow96)
                column96 = math.ceil(rawColumn96)

                return ((row96 * PlateMapUtil.COLUMN_COUNT_96)- PlateMapUtil.COLUMN_COUNT_96 ) + column96

	def location_384_to_quadrant(self,location_index):
                row384 = self.row_number_lookup_by_index(PlateMapUtil.PLATE_TYPE_384,location_index)
                column384 =  self.column_number_lookup_by_index(PlateMapUtil.PLATE_TYPE_384,location_index)

		quadrant = self.quadrant_lookup(row384,column384)

                return quadrant

	def quadrant_lookup(self,row,column):
                rowBin = row%2
                columnBin = column%2
                return "{0}{1}".format(rowBin,columnBin)
