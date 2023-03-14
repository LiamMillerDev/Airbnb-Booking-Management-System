<!DOCTYPE HTML>
<html>

<head>
    <title>Manage reviews</title>
</head>

<body>

    <?php
    include "config.php"; //load in any variables
    include "cleaninput.php";
    include "checksession.php";
    checkuser();

    $db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);
    $error = 0;
    if (mysqli_connect_errno()) {
        echo "Error: Unable to connect to MySQL. " . mysqli_connect_error();
        exit; //stop processing the page further
    };

    //retrieve the roomid from the URL
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $id = $_GET['id'];
        if (empty($id) or !is_numeric($id)) {
            echo "<h2>Invalid booking ID</h2>"; //simple error feedback
            exit;
        }
    }
    //the data was sent using a formtherefore we use the $_POST instead of $_GET
    //check if we are saving data first by checking if the submit button exists in the array
    if (isset($_POST['submit']) and !empty($_POST['submit']) and ($_POST['submit'] == 'Update')) {
        //validate incoming data - only the first field is done for you in this example - rest is up to you do

        //roomID (sent via a form ti is a string not a number so we try a type conversion!)    
        if (isset($_POST['id']) and !empty($_POST['id']) and is_integer(intval($_POST['id']))) {
            $id = cleanInput($_POST['id']);
        } else {
            $error++; //bump the error flag
            $msg .= 'Invalid booking ID '; //append error message
            $id = 0;
        }
        $review = cleanInput($_POST['review']);

        //save the room data if the error flag is still clear and room id is > 0
        if ($error == 0 and $id > 0) {
            $query = "UPDATE booking SET review=? WHERE bookingID=?";
            $stmt = mysqli_prepare($db_connection, $query); //prepare the query
            mysqli_stmt_bind_param($stmt, 'si', $review, $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            echo "<h2>Booking review updated.</h2>";
        } else {
            echo "<h2>$msg</h2>";
        }
    }
    //locate the room to edit by using the roomID
    //we also include the room ID in our form for sending it back for saving the data
    $query = 'SELECT review FROM booking WHERE bookingID=' . $id;
    $result = mysqli_query($db_connection, $query);
    $rowcount = mysqli_num_rows($result);
    if ($rowcount > 0) {
        $row = mysqli_fetch_assoc($result);

    ?>

        <h1>Booking Details Update</h1>
        <h2><a href='listbookings.php'>[Return to the bookings listing]</a><a href='index.php'>[Return to the main page]</a></h2>

        <form method="POST" action="managereviews.php">

            <input type="hidden" name="id" value="<?php echo $id; ?>">

            <p>
                <label for="review">Review:</label>
                <input type="text" name="review" id="review" value="<?php echo $row['review']; ?>">
            </p>
            <input type="submit" name="submit" value="Update">
        </form>
    <?php
    } else {
        echo "<h2>booking not found with that ID</h2>"; //simple error feedback
    }
    mysqli_close($db_connection); //close the connection once done
    ?>
</body>

</html>