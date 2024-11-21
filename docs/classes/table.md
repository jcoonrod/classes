# Table Class
* This formats and outputs the results of database queries into tables
* It contains ->contents is a 2d array that gets displayed by the show method
* This facilitates very fancy grids with links, towspans and other useful formats
## Methods
* start($db) // must pass the database connection in order for query to work
* info($definition) // return a string function with info symbol and title
* rowspan($n=2){ // set number of columns to include in rowspan
* fetchRecords($query) // an addition not by me - not sure we need it
* query($query) // load results of a query into the grid
* column($id_col,$dest_col,$array) //
* map($id_col,$dest_col,$array) //
* map_query($id_col,$dest_col,$query)
* loadrows($result) { // load from the output of a pdo query
* dump() // for debug only: print_r($this->contents);
* backmap($id_col) // Identify the first row of a rowspan set of rows
* ntext($n=1){ // set the number of text columns
* groups($row,$showGroupID=TRUE)
* inforow($array) // hover over icon to see relevant information about the row
* infocol($array) // ditto for column
* totals($col1=2,$row1=1){ // SUM UP THE $contents from column $col1 onwards (counting from zero)
* sumrows($col1=2,$row1=1) // just an alias
* sumcols($col1=2,$row1=1){ // sum the last columns starting with $n to a new Total column, startin with row 1
