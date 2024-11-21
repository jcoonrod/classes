# Table Class
* This formats and outputs the results of database queries into tables
## Public Properties
* contents is a 2d array that gets displayed by the show method
## Methods - Basic/Minimal
* start($db) // must pass the database connection in order for query to work
* query($query) // load results of a query into the grid
* show($href='') // display the table. If href (link) provided then the first visible column has a link to $href.value of col0
## Methods - Intermediate, used often
* info($definition) // returns a string function with info symbol and title
* rowspan($n=2){ // set number of columns to include in rowspan
* groups($row,$showGroupID=TRUE)
* inforow($array) // hover over icon to see relevant information about the row
* infocol($array) // ditto for column
* ntext($n=1) // set the number of text columns, eg - don't do numeric formatting to these columns
* sumrows($col1=2,$row1=1) // just an alias
* sumcols($col1=2,$row1=1){ // sum the last columns starting with $n to a new Total column, startin with row 1
## Methods - Advanced - append new columns by mapping contents of a sparse array against an indicator column
* column($id_col,$dest_col,$array) //
* map($id_col,$dest_col,$array) //
* map_query($id_col,$dest_col,$query)
* loadrows($result) { // load from the output of a pdo query
* dump() // for debug only: print_r($this->contents);
* backmap($id_col) // Identify the first row of a rowspan set of rows 
