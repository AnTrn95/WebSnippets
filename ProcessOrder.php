<?php // UTF-8 marker äöüÄÖÜß€
include('Pizzaservice.php');
if (!empty($_POST["pizzen"]) && !empty($_POST["kundendaten"])) {
    Pizzaservice::notify();
}

/**
 * $service = new Pizzaservice();
 * $service->processReceivedData();
 */
class ProcessOrder        // to do: change name of class
{
    // --- ATTRIBUTES ---
    private $address;
    private $pizza_name;
    /**
     * Reference to the MySQLi-Database that is
     * accessed by all operations of the class.
     */
    protected $_database = null;


    public function __construct($database)
    {
        $this->_database = $database;
        // to do: instantiate members representing substructures/blocks
    }

    /**
     * Fetch all data that is necessary for later output.
     * Data is stored in an easily accessible way e.g. as associative array.
     *
     * @return none
     */
    protected function getViewData()
    {
        // to do: fetch data for this view from the database
    }

    /**
     * Generates an HTML block embraced by a div-tag with the submitted id.
     * If the block contains other blocks, delegate the generation of their
     * parts of the view to them.
     *
     * @param $id $id is the unique (!!) id to be used as id in the div-tag
     *
     * @return none
     */
    public function generateView($id = "")
    {
        $this->getViewData();
        if ($id) {
            $id = "id=\"$id\"";
        }
        echo "<div $id>\n";
        // to do: call generateView() for all members
        echo "</div>\n";
    }


    /**
     * Processes the data that comes via GET or POST i.e. CGI.
     * If this block is supposed to do something with submitted
     * data do it here.
     * If the block contains other blocks, delegate processing of the
     * respective subsets of data to them.
     *
     * @return none
     */

    public function processReceivedData()
    {
        if (isset($_POST["pizzen"]) && isset($_POST["kundendaten"])) {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $p_selection = $_POST["pizzen"];
                $client_data = $_POST["kundendaten"];

                $pos_after_first_comma = strpos($client_data, ',') + boolval(strpos($client_data, ','));  // boolval == 0: kein Komma
                $this->address = substr($client_data, $pos_after_first_comma, strlen($client_data));

                $this->address = trim($this->address, " "); // remove whitespace
                $this->address = htmlspecialchars_decode($this->address);
                $this->address = utf8_decode($this->address);
                $this->sendOrderData();
                //print_r($address);

                for ($i = 0; $i < count($p_selection); $i++) {
                    $pos_after_first_comma = strpos($p_selection[$i], 'x') + boolval(strpos($p_selection[$i], 'x'));  // boolval == 0: kein Komma
                    $this->pizza_name = substr($p_selection[$i], $pos_after_first_comma, strlen($p_selection[$i]));
                    $this->pizza_name = trim($this->pizza_name, " "); // remove whitespace
                    $this->pizza_name = htmlspecialchars_decode($this->pizza_name);
                    $this->pizza_name = utf8_decode($this->pizza_name);
                    $this->sendPizzaOrderData();
                }
            }
        }

        // to do: call processData() for all members
    }

    private function sendOrderData()
    {

        mysqli_query($this->_database, "insert into bestellung(Adresse) values('$this->address')");

    }

    private function sendPizzaOrderData()
    {
        $result = mysqli_query($this->_database, "select max(BestellungID) from Bestellung");
        $maxID = mysqli_fetch_row($result);
        print_r($this->pizza_name);
        mysqli_query($this->_database, "insert into BestelltePizza(fBestellungID, fPizzaName) values($maxID[0], '$this->pizza_name')");
    }
}

