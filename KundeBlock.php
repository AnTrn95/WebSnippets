<?php // UTF-8 marker äöüÄÖÜß€
include_once('./Kunde.php');

if (!empty($_GET)) {
    Kunde::notify();
}

class KundeBlock     // to do: change name of class
{
    // --- ATTRIBUTES ---
    protected $_database = null;
    private $result;
    // to do: declare reference variables for members 
    // representing substructures/blocks

    // --- OPERATIONS ---


    public function __construct($database)
    {
        $this->_database = $database;
        // to do: instantiate members representing substructures/blocks
    }

    protected function getViewData()
    {
        $orderID_of_last_session =0;
        // to do: fetch data for this view from the database
        if( isset($_SESSION['auftragsNR'])) $orderID_of_last_session = $_SESSION['auftragsNR'];

        $this->result = mysqli_query($this->_database, "Select * from BestelltePizza where fBestellungID= $orderID_of_last_session[0]");
    }

    public function generateView()
    {
        $this->getViewData();
        $first_status = 'bestellt';
        $sec_status = 'im Ofen';
        $third_status = 'fertig';
        $forth_status = 'unterwegs';

        $name = null;
        $status = null;

        if(!empty($this->result)) {
            echo "<table class='delivery-status'>\n";
            echo "<tr><th></th><th>$first_status</th><th>$sec_status</th><th>$third_status</th><th>$forth_status</th></tr>\n";

            while ($row = mysqli_fetch_assoc($this->result)) {
                $check1 = "";
                $check2 = "";
                $check3 = "";
                $check4 = "";

                $name = $row['fPizzaName'];
                $status = $row['Status'];
                $p_id = $row['PizzaID'];
                if ($status == $first_status) $check1 = 'checked';
                if ($status == $sec_status) $check2 = 'checked';
                if ($status == $third_status) $check3 = 'checked';
                if ($status == $forth_status) $check4 = 'checked';

                echo "<tr>\n";
                echo "<td class='pizza-ordered' >$name</td>";
                echo "<td><input title='' name='$p_id' type='radio' value='$first_status' disabled $check1/></td>";
                echo "<td><input title='' name='$p_id' type='radio' value='$sec_status' disabled $check2/></td>";
                echo "<td><input title='' name='$p_id' type='radio' value='$third_status' disabled $check3/></td>";
                echo "<td><input title='' name='$p_id' type='radio' value='$forth_status' disabled $check4/></td>";
                echo "</tr>";

            }
            echo "</table>";
        }
    }
    public function processReceivedData()
    {
    }

}
// Zend standard does not like closing php-tag!
// PHP doesn't require the closing tag (it is assumed when the file ends). 
// Not specifying the closing ? >  helps to prevent accidents 
// like additional whitespace which will cause session 
// initialization to fail ("headers already sent"). 
//? >