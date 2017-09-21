<?php
require_once("db/class_dbconn.php");

Class Parse
{

    public function __construct()
    {

    }

    public function Air()
    {
        $db = DBConn::getConnection(); // connect to db

        $csv = fopen('csv/AIR.csv', 'r'); //open csv file as read only and assign to $csv variable

        $flag = true;
        while (($data=fgetcsv($csv, 10000, ",")) != false) {

            if($flag){ //skip the first line of the file
                $flag = false;
                continue;
            }


            //assign each field to a variable. If the field is empty assign it as NULL.
            $agency_id = empty($data['0']) ? 'NULL' : "'" . $data['0'] . "'";
            $pax_id = empty($data['1']) ? 'NULL' : "'" . $data['1'] . "'";
            $booking_id = empty($data['2']) ? 'NULL' : "'" . $data['2'] . "'";
            $ar_trans_id = empty($data['3']) ? 'NULL' : "'" . $data['3'] . "'";
            $agt_id = empty($data['4']) ? 'NULL' : "'" . $data['4'] . "'";
            $ar_tran_date = empty($data['5']) ? 'NULL' : "'" . date('Y-m-d', strtotime($data[5])) . "'"; //format the date fields.
            $ar_tran_time = empty($data['6']) ? 'NULL' : "'" . $data['6'] . "'";
            $ar_mode = empty($data['7']) ? 'NULL' : "'" . $data['7'] . "'";
            $ar_oper = empty($data['8']) ? 'NULL' : "'" . $data['8'] . "'";
            $ar_flight_num = empty($data['9']) ? 'NULL' : "'" . $data['9'] . "'";
            $ar_class = empty($data['10']) ? '"A"' : "'" . $data['10'] . "'";
            $ar_dep_date = empty($data['11']) ? 'NULL' : "'" . date('Y-m-d', strtotime($data[11])) . "'";
            $ar_arr_date = empty($data['12']) ? 'NULL' : "'" . date('Y-m-d', strtotime($data[12])) . "'";
            $ar_dep_city = empty($data['13']) ? '"N/A"' : "'" . $data['13'] . "'";
            $ar_arr_city = empty($data['14']) ? '"N/A"' : "'" . $data['14'] . "'";
            $ar_dep_time = empty($data['15']) ? '"N/A"' : "'" . $data['15'] . "'";
            $ar_arr_time = empty($data['16']) ? '"N/A"' : "'" . $data['16'] . "'";
            $ar_maj_orig = empty($data['17']) ? '"N/A"' : "'" . $data['17'] . "'";
            $ar_maj_dest = empty($data['18']) ? '"N/A"' : "'" . $data['18'] . "'";
            $ar_base_val = empty($data['19']) ? '00.00' : "'" . $data['19'] . "'";
            $ar_tax_val = empty($data['20']) ? '00000' : "'" . $data['20'] . "'";
            $ar_ttl_comm = empty($data['21']) ? '0' : "'" . $data['21'] . "'";
            $ar_tkt_num = empty($data['22']) ? '"N/A"' : "'" . $data['22'] . "'";
            $ar_conf_num = empty($data['23']) ? '"N/A"' : "'" . $data['23'] . "'";
            $ar_exch = empty($data['24']) ? 'NULL' : "'" . $data['24'] . "'";
            $ar_exch_val = empty($data['25']) ? 'NULL' : "'" . $data['25'] . "'";
            $ar_exch_orig = empty($data['26']) ? 'NULL' : "'" . $data['26'] . "'";
            $ar_hi_fare = empty($data['27']) ? 'NULL' : "'" . $data['27'] . "'";
            $ar_lo_fare = empty($data['28']) ? 'NULL' : "'" . $data['28'] . "'";
            $ar_reason = empty($data['29']) ? 'NULL' : "'" . $data['29'] . "'";
            $ar_inv_num = empty($data['30']) ? '"00.00"' : "'" . $data['30'] . "'";
            $ar_pax_count = empty($data['31']) ? 'NULL' : "'" . $data['31'] . "'";



            //prepare row for insertion. For the final 3 columns create placeholders as they will be overwritten in the next sql statement
            $row = "$agency_id, $pax_id, $booking_id, $ar_trans_id, $agt_id, $ar_tran_date, $ar_tran_time, $ar_mode, $ar_oper, $ar_flight_num,
                    $ar_class, $ar_dep_date, $ar_arr_date, $ar_dep_city, $ar_arr_city, $ar_dep_time, $ar_arr_time, $ar_maj_orig, $ar_maj_dest,
                    $ar_base_val, $ar_tax_val, $ar_ttl_comm, $ar_tkt_num, $ar_conf_num, $ar_exch, $ar_exch_val, $ar_exch_orig, $ar_hi_fare,
                    $ar_lo_fare, $ar_reason, $ar_inv_num, $ar_pax_count, 'AAA' , 0 , '2017-01-01'";
            //insert values into the database.
            $sql = "INSERT INTO air (agency_id, pax_id, booking_id, ar_trans_id, agt_id, ar_tran_date, ar_tran_time, ar_mode, ar_oper,
                                      ar_flight_num, ar_class, ar_dep_date, ar_arr_date, ar_dep_city, ar_arr_city, ar_dep_time, ar_arr_time,
                                      arr_maj_orig, ar_maj_dest, ar_base_val, ar_tax_val, ar_ttl_comm, ar_tkt_num, ar_conf_num, ar_exch,
                                      ar_exch_val, ar_exch_orig, ar_hi_fare, ar_lo_fare, ar_reason, ar_inv_num, ar_pax_count, acct_id, inv_val, inv_tran_date)
                    VALUES ($row)
                    ON DUPLICATE KEY UPDATE
                    agency_id = $agency_id,
                    pax_id = $pax_id,
                    booking_id = $booking_id";

            $db->query($sql);

            //this sql statement inserts the transaction time, invoice value and invoice date from the invoice table into the air table for each row.
            $sqlupdatetime = "UPDATE air a
                                    INNER JOIN invoices b
                                    ON a.booking_id = b.booking_id AND a.pax_id = b.pax_id
                                    SET a.ar_tran_time = b.inv_tran_time, a.inv_val = b.inv_val, a.inv_tran_date = b.inv_tran_date
                                    WHERE a.pax_id = $pax_id
                                    AND b.pax_id = $pax_id
                                    AND a.ar_tran_date = $ar_tran_date
                                    AND b.tran_id = $ar_trans_id";
            $db->query($sqlupdatetime);

            //this sql statement inserts the acct_id from the head table into the air table for each row.
            $sqlaccountid = "UPDATE air a
                            INNER JOIN head b
                            ON a.booking_id = b.booking_id AND a.pax_id = b.pax_id 
                            SET a.acct_id = b.acct_id
                            WHERE a.pax_id = $pax_id
                            AND b.pax_id = $pax_id
                            AND ar_tran_date = $ar_tran_date";

            $db->query($sqlaccountid);
        }

        fclose($csv);
    }

    public function Head()
    {
        $db = DBConn::getConnection(); // connect to db

        $csv = fopen('csv/HEAD.csv', 'r'); //open csv file as read only and assign to $csv variable

        $flag = true;
        while (($data=fgetcsv($csv, 10000, ",")) != false) {

            if($flag){ //skip the first line of the file
                $flag = false;
                continue;
            }

            //assign each field to a variable. If the field is empty assign it as NULL.
            $agency_id = empty($data['0']) ? 'NULL' : "'" . $data['0'] . "'";
            $pax_id = empty($data['1']) ? 'NULL' : "'" . $data['1'] . "'";
            $booking_id = empty($data['2']) ? 'NULL' : "'" . $data['2'] . "'";
            $acct_id = empty($data['3']) ? 'NULL' : "'" . $data['3'] . "'";
            $branch_num = empty($data['4']) ? 'NULL' : "'" . $data['4'] . "'";
            $agt_id = empty($data['5']) ? 'NULL' : "'" . $data[5] . "'"; //format the date fields.
            $pax_title = empty($data['6']) ? 'NULL' : "'" . $data['6'] . "'";
            $pax_fname = empty($data['7']) ? 'NULL' : "'" . $data['7'] . "'";
            $pax_sname = empty($data['8']) ? 'NULL' : "'" . $data['8'] . "'";
            $tran_date = empty($data['9']) ? 'NULL' : "'" . $data['9'] . "'";
            $tran_time = 0;



            //prepare row for insertion
            $row = "$agency_id, $pax_id, $booking_id, $acct_id, $branch_num, $agt_id, $pax_title, $pax_fname, $pax_sname, $tran_date, $tran_time";
            //insert values into the database. If the generated CCT_KEY is duplicated, update the row in the database.
            $sql = "INSERT INTO head (agency_id, pax_id, booking_id, acct_id, branch_num, agt_id, pax_title, pax_fname, pax_sname, tran_date, tran_time)
                    VALUES ($row)
                    ON DUPLICATE KEY UPDATE
                    agency_id = $agency_id,
                    pax_id = $pax_id,
                    booking_id = $booking_id
                    ";

            $db->query($sql);
        }

        fclose($csv);
    }

    public function Invoices()
    {
        $db = DBConn::getConnection(); // connect to db

        $csv = fopen('csv/INV.csv', 'r'); //open csv file as read only and assign to $csv variable

        $flag = true;
        while (($data=fgetcsv($csv, 100000, ",")) != false) {

            if($flag){ //skip the first line of the file
                $flag = false;
                continue;
            }

            //assign each field to a variable. If the field is empty assign it as NULL.
            $agency_id = empty($data['0']) ? 'NULL' : "'" . $data['0'] . "'";
            $pax_id = empty($data['1']) ? 'NULL' : "'" . $data['1'] . "'";
            $booking_id = empty($data['2']) ? 'NULL' : "'" . $data['2'] . "'";
            $tran_id = empty($data['3']) ? 'NULL' : "'" . $data['3'] . "'";
            $inv_type = empty($data['4']) ? 'NULL' : "'" . $data['4'] . "'";
            $inv_tran_date = empty($data['5']) ? 'NULL' : "'" . date('Y-m-d', strtotime($data[5])) . "'"; //format the date fields.
            $inv_tran_time = empty($data['6']) ? '"N/A"' : "'" . $data['6'] . "'";
            $inv_val = empty($data['7']) ? '0' : "" . $data['7'] . "";
            $inv_num = "'" . $data['8'] . "'";



            //prepare row for insertion
            $row = "$agency_id, $pax_id, $booking_id, $tran_id, $inv_type, $inv_tran_date, $inv_tran_time, $inv_val, $inv_num";

            //insert values into the database.
            if ($inv_val != 0){
                $sql = "INSERT INTO invoices (agency_id, pax_id, booking_id, tran_id, inv_type, inv_tran_date, inv_tran_time, inv_val, inv_num)
                    VALUES ($row)
                    ON DUPLICATE KEY UPDATE
                    agency_id = $agency_id,
                    pax_id = $pax_id,
                    booking_id = $booking_id";

                $db->query($sql);


                //this sql statement inserts the transaction time from the invoice table into the head table for each row.
                $sqlupdatehead = "UPDATE head a
                                    INNER JOIN invoices b
                                    ON a.booking_id = b.booking_id
                                    SET a.tran_time = b.inv_tran_time
                                    WHERE a.pax_id = $pax_id
                                    AND b.pax_id = $pax_id
                                    AND a.tran_date = $inv_tran_date
                                    AND b.tran_id = $tran_id";
                $db->query($sqlupdatehead);
            }

        }

        fclose($csv);
    }

}
