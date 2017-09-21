<?php
        require_once('class_calculate.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../../../favicon.ico">

    <title>Travelogix Interview Project</title>

    <!-- Bootstrap core CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="resources/css/app.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="resources/js/app.js"></script>
</head>

<body>

<div class="container">
    <div class="starter-template">
        <h1>Travelogix Interview Project</h1>
    </div>

    <div class="content-container">
        <div class="content-left">
            <h3>Click below to update the mysql database with content from the csv files.</h3> <br/>
            <button type=button class="btn btn-lg btn-primary btn-block">Update</button>
        </div>
        <div class="content-right">
            <?php
                $calculate = new Calculate;
            ?>
            <table id=statstable class="table table-hover">
                <tbody>
                    <tr>
                        <td><b>Most common destination</b></td>
                        <td><?php echo $calculate->mostCommonDestination()?></td>
                    </tr>
                    <tr>
                        <td><b>Average flight fare cost (base + tax)</b></td>
                        <td>£<?php echo $calculate->averageFlightFare()?></td>
                    </tr>
                    <tr>
                        <td><b>Top Spending Account Codes</b></td>
                        <td><tr><td><b>Account ID</b></td><td><b>Total Spending</b></td></tr><?php echo $calculate->topSpendingAccountCodes()?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div><!-- /.container -->
</body>

</html>
