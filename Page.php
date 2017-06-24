<?php // UTF-8 marker äöüÄÖÜß€

/**
 * This abstract class is a common base class for all
 * HTML-pages to be created.
 * It manages access to the database and provides operations
 * for outputting header and footer of a page.
 * Specific pages have to inherit from that class.
 * Each inherited class can use these operations for accessing the db
 * and for creating the generic parts of a HTML-page.
 *
 * @author   Bernhard Kreling, <b.kreling@fbi.h-da.de>
 * @author   Ralf Hahn, <ralf.hahn@h-da.de>
 */
abstract class Page
{
// --- ATTRIBUTES ---

/**
 * Reference to the MySQLi-Database that is
 * accessed by all operations of the class.
 */
protected $_database = null;

// --- OPERATIONS ---

/**
 * Connects to DB and stores
 * the connection in member $_database.
 * Needs name of DB, user, password.
 *
 * @return none
 */
protected function __construct()
{
$servername = "localhost";
$username = "root";
$password = "";
$dbName= "ewass17";
// Create connection
$this->_database = new mysqli($servername, $username, $password, $dbName); /* to do: create instance of class MySQLi */;

// Check connection
if ($this->_database->connect_error) {
    die("Connection failed: " . $this->_database->connect_error);
}
echo "Connected to database";

}

/**
* Closes the DB connection and cleans up
*
* @return none
*/
protected function __destruct()
{
// to do: close database
    $this->_database->close();
}

/**
* Generates the header section of the page.
* i.e. starting from the content type up to the body-tag.
* Takes care that all strings passed from outside
* are converted to safe HTML by htmlspecialchars.
*
* @param $headline $headline is the text to be used as title of the page
*
* @return none
*/
protected function generatePageHeader($headline = "")
{
$headline = htmlspecialchars($headline);
header("Content-type: text/html; charset=UTF-8");

// to do: output common beginning of HTML code
// including the individual headline
}

/**
* Outputs the end of the HTML-file i.e. /body etc.
*
* @return none
*/
protected function generatePageFooter()
{
// to do: output common end of HTML code
}

/**
* Processes the data that comes via GET or POST i.e. CGI.
* If every page is supposed to do something with submitted
* data do it here. E.g. checking the settings of PHP that
* influence passing the parameters (e.g. magic_quotes).
*
* @return none
*/
protected function processReceivedData()
{
if (get_magic_quotes_gpc()) {
throw new Exception
("Bitte schalten Sie magic_quotes_gpc in php.ini aus!");
}
}
} // end of class

// Zend standard does not like closing php-tag!
// PHP doesn't require the closing tag (it is assumed when the file ends).
// Not specifying the closing ? >  helps to prevent accidents
// like additional whitespace which will cause session
// initialization to fail ("headers already sent").
//? >