<?php
require_once("db/class_dbconn.php");

Class Calculate
{

    public function __construct()
    {

    }

    public function averageFlightFare()
    {
        $db = DBConn::getConnection(); //connect to db

        $sql = "SELECT AVG(ar_base_val + ar_tax_val) as Average
                FROM air";


        $result = $db->query($sql);
        $row = $result->fetch(PDO::FETCH_ASSOC);

        echo number_format((float)current($row),2,'.','');

    }

    public function mostCommonDestination()
    {
        $db = DBConn::getConnection(); // connect to db

        //select which destination appears the most times in the air table. due to some fields being empty, N/A is the most common occurrence,
        // so disregard that and display the next one.
        $sql = "SELECT ar_maj_dest
                FROM air
                WHERE ar_maj_dest != 'N/A'
                GROUP BY ar_maj_dest
                ORDER BY count(ar_maj_dest) DESC
                LIMIT 1";

        $result = $db->query($sql);
        $row = $result->fetch(PDO::FETCH_ASSOC);

        echo current($row); //echos the first result of the array, which in this case the array only has 1 result.
    }

    public function topSpendingAccountCodes()
    {
        $db = DBConn::getConnection(); // connect to db
        //select the account id and the sum of the invoice values from that account ID. Then order the list to show the 3 biggest spenders.
        $sql = "SELECT acct_id, sum(`inv_val`) AS sinv FROM head a
                INNER JOIN invoices b
                ON a.pax_id = b.pax_id AND a.booking_id = b.booking_id
                GROUP BY acct_id
                ORDER BY sum(`inv_val`) DESC
                LIMIT 3";



        $result = $db->query($sql);

        while($row = $result->fetch(PDO::FETCH_ASSOC)){
            echo "<tr><td>" . $row['acct_id'] . "</td><td>£" . number_format((float)$row['sinv'],2,".","") . "</td></tr>";
        }
    }
}
