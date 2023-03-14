<!DOCTYPE HTML>

<?php
include "checksession.php";
checkUser();
?>

<html>

<head>
    <title>View Bookings</title>
</head>

<body>
    <?php
    include "config.php"; //load in any variables
    $db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);
    if (mysqli_connect_errno()) {
        echo "Error: Unable to connect to MySQL. " . mysqli_connect_error();
        exit; //stop processing the page further
    }
    $id = $_GET['id'];
    if (empty($id) or !is_numeric($id)) {
        echo "<h2>Invalid bookingID</h2>"; //simple error feedback
        exit;
    }

    $query = 'SELECT * FROM booking WHERE bookingID=' . $id;
    $result = mysqli_query($db_connection, $query);
    $rowcount = mysqli_num_rows($result);
    ?>
    <h1>Booking Details View</h1>
    <h2><a href='listbookings.php'>[Return to the Booking listing]</a><a href='index.php'>[Return to the main page]</a></h2>

    <?php
    if ($rowcount > 0) {
        echo "<fieldset><legend>booking detail #$id</legend><dl>";
        $row = mysqli_fetch_assoc($result);
        echo "<dt>Room:</dt><dd>" . $row['roomchoice'] . "</dd>";
        echo "<dt>First name:</dt><dd>" . $row['firstname'] . "</dd>";
        echo "<dt>Last name:</dt><dd>" . $row['lastname'] . "</dd>";
        echo "<dt>CheckIn:</dt><dd>" . $row['checkin'] . "</dd>";
        echo "<dt>CheckOut:</dt><dd>" . $row['checkout'] . "</dd>";
        echo "<dt>Contact Number:</dt><dd>" . $row['contact'] . "</dd>";
        echo "<dt>Extras:</dt><dd>" . $row['extra'] . "</dd>";
        echo "<dt>Review:</dt><dd>" . $row['review'] . "</dd>";
        echo "</dl></fieldset>";
    } else {
        echo "<h2>No booking found!</h2>"; //suitable feedback
    }
    mysqli_free_result($result); //free any memory used by the query
    mysqli_close($db_connection); //close the connection once done
    ?>
</body>

</html>