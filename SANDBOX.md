this file shall describe latest sandbox functionality

v0.2.1 - <na-tabley with <na-table-datasource>
	display a sortable table. its columns shall be set dynamicly
	if <na-table> has <na-field> children, use them for column definition
	if <na-table> has fields="" attribute, use only those fields
	<na-table-datasoruce> is connected to sortable-table and na-table
	<na-table-datasoruce> calls ninja ModAdminApi module to fetch data and meta info
