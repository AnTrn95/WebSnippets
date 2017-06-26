<?php // UTF-8 marker äöüÄÖÜß€
include_once('./Baker.php');

if(!empty($_GET)) {
    Baker::notify();
}

class BaeckerStatusBlock        // to do: change name of class
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
        // to do: fetch data for this view from the database
        $this->result = mysqli_query($this->_database, "Select * from BestelltePizza where lower(status)= 'bestellt' OR status='im ofen' ");
    }

    public function generateView()
    {
        $this->getViewData();
        $first_status = 'bestellt';
        $sec_status = 'im Ofen';
        $third_status = 'fertig';
        $name = null;
        $status = null;
        $state_name = 1;
        $formID_Counter = 1;

        echo "<table class='order-status'>\n";
        echo "<tr><th></th><th>$first_status</th><th>$sec_status</th><th>$third_status</th></tr>\n";

        while ($row = mysqli_fetch_assoc($this->result)) {
            $formID = $formID_Counter . '-form';
            $check1 = "";
            $check2 = "";
            $check3 = "";

            $name = $row['fPizzaName'];
            $status = $row['Status'];
            $p_id=  $row['PizzaID'];
            if ($status == $first_status) $check1 = 'checked';
            if ($status == $sec_status) $check2 = 'checked';
            if ($status == $third_status) $check3 = 'checked';



            echo "<form action='BaeckerStatusBlock.php' id='$formID' method='get' accept-charset='UTF-8'>";

            echo "<tr onclick=document.forms['$formID'].submit();>\n";
            echo "<td class='pizza-ordered'>$name</td>";
            echo "<td><input title='' name='$p_id' type='radio' value='$first_status' $check1/></td>";
            echo "<td><input title='' name='$p_id' type='radio' value='$sec_status' $check2/></td>";
            echo "<td><input title='' name='$p_id'  type='radio' value='$third_status' $check3/></td>";
            echo "</tr></form>";

            $state_name++;
            $formID_Counter++;
        }
        echo "</table>";
    }

    public function processReceivedData()
    {
        if(!empty($_GET)) {
            $getVars = array_keys($_GET);
            $p_id = mysqli_real_escape_string($this->_database,$getVars[0]);
            $status = mysqli_real_escape_string($this->_database,$_GET[$getVars[0]]);

            if ($_SERVER["REQUEST_METHOD"] == "GET") {
                mysqli_query($this->_database, "update BestelltePizza set Status='$status' where PizzaID= $p_id");

            }
            // to do: call processData() for all members
        }
    }

}
// Zend standard does not like closing php-tag!
// PHP doesn't require the closing tag (it is assumed when the file ends). 
// Not specifying the closing ? >  helps to prevent accidents 
// like additional whitespace which will cause session 
// initialization to fail ("headers already sent"). 
//? >