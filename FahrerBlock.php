<?php // UTF-8 marker äöüÄÖÜß€
include_once('Fahrer.php');

if(!empty($_POST)) {
    Fahrer::notify();
}


class FahrerBlock        // to do: change name of class
{

    protected $_database = null;
    private $result;
    private $address;
    private $sum;
    private $orderID;
    public function __construct($database)
    {
        $this->_database = $database;

    }


    public function getViewData()
    {
        // to do: fetch data for this view from the database
        $result = mysqli_query($this->_database, "select max(BestellungID) from Bestellung");
        $maxID = mysqli_fetch_row($result);

        echo "<div class='client-info'>";
        for ($orderID = 1; $orderID <= $maxID[0]; $orderID++) {

            $this->result = mysqli_query($this->_database, "select * from bestelltepizza inner join bestellung on BestellungID= fBestellungID where fBestellungID=$orderID and (status='unterwegs' or status='fertig' and status= ALL(select Status from bestelltepizza where fBestellungID=$orderID))");
            if(mysqli_num_rows($this->result)) {

            $this->sum = mysqli_query($this->_database, "select sum(Preis) from (Bestellung inner join BestelltePizza on BestellungID= fBestellungID) inner join Angebot on pizzaName=fPizzaName where bestellungID=$orderID group by BestellungID");
            $this->sum = mysqli_fetch_row($this->sum)[0];

                $this->generateView();
            }
        }
        echo "</div>";
    }

    public function generateView()
    {
        $first_status = 'fertig';
        $sec_status = 'unterwegs';
        $third_status = 'geliefert';
        $name = null;
        $status = null;
        $stepCounter = 0;
        $check1 = "";
        $check2 = "";
        $check3 = "";

        while ($row = mysqli_fetch_assoc($this->result)) {
            if ($stepCounter != 0) $name .= ', ';
            else {
                $this->orderID = 'form-'.$row['fBestellungID'];
            }
            $name .= $row['fPizzaName'];

            $status = $row['Status'];
            $this->address = utf8_encode($row['Adresse']);
            if ($status == $first_status) $check1 = 'checked';
            if ($status == $sec_status) $check2 = 'checked';
            if ($status == $third_status) $check3 = 'checked';
            $stepCounter++;
        }

        echo "<section>\n";
        echo "<h4>$this->address</h4>\n";
        echo "<p>$name</p>\n";
        echo "<p>Preis: <span class='sum-order' data-price>$this->sum €</span></p>\n";

        echo "<table title=''>";


        echo "<tr><th>$first_status</th><th>$sec_status</th><th>$third_status</th></tr>\n";
        echo "<form action='FahrerBlock.php' id='$this->orderID' method='post' accept-charset='UTF-8'>\n";
        echo "<tr onclick=document.forms['$this->orderID'].submit();>\n";
        echo "<td><input title='' name='$this->orderID' type='radio' value='$first_status' $check1/></td>\n";
        echo "<td><input title='' name='$this->orderID' type='radio' value='$sec_status' $check2/></td>\n";
        echo "<td><input title='' name='$this->orderID' type='radio' value='$third_status' $check3/></td>\n";
        echo "</tr></form></table></section>";

    }

    public function processReceivedData()
    {
        $getVars = array_keys($_POST);
        $formID = mysqli_real_escape_string($this->_database,$getVars[0]);
        $pos_after_first_comma = strpos($formID, '-') + boolval(strpos($formID, '-'));  // boolval == 0: kein Komma
        $id = substr($formID, $pos_after_first_comma, strlen($formID));

        $this->address = trim($this->address, " "); // remove whitespace
        $status = mysqli_real_escape_string($this->_database,$_POST[$getVars[0]]);

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            mysqli_query($this->_database, "update BestelltePizza set Status='$status' where fBestellungID= $id");
        }
        // to do: call processData() for all members

    }
}
// Zend standard does not like closing php-tag!
// PHP doesn't require the closing tag (it is assumed when the file ends). 
// Not specifying the closing ? >  helps to prevent accidents 
// like additional whitespace which will cause session 
// initialization to fail ("headers already sent"). 
//? >