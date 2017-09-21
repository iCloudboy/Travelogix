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
        
    }
}
