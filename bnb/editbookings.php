<!DOCTYPE HTML>
<html>

<head>
    <title>Edit a booking</title>
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
        $roomchoice = cleanInput($_POST['roomchoice']);
        $firstname = cleanInput($_POST['firstname']);
        $lastname = cleanInput($_POST['lastname']);
        $checkin = cleanInput($_POST['checkin']);
        $checkout = cleanInput($_POST['checkout']);
        $contact = cleanInput($_POST['contact']);
        $extra = cleanInput($_POST['extra']);
        $review = cleanInput($_POST['review']);

        //save the room data if the error flag is still clear and room id is > 0
        if ($error == 0 and $id > 0) {
            $query = "UPDATE booking SET roomchoice=?,firstname=?,lastname=?,checkin=?,checkout=?,contact=?,extra=?,review=? WHERE bookingID=?";
            $stmt = mysqli_prepare($db_connection, $query); //prepare the query
            mysqli_stmt_bind_param($stmt, 'ssssssssi', $roomchoice, $firstname, $lastname, $checkin, $checkout, $contact, $extra, $review, $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            echo "<h2>Booking details updated.</h2>";
        } else {
            echo "<h2>$msg</h2>";
        }
    }
    //locate the room to edit by using the roomID
    //we also include the room ID in our form for sending it back for saving the data
    $query = 'SELECT roomchoice, firstname, lastname, checkin, checkout, contact, extra, review FROM booking WHERE bookingID=' . $id;
    $result = mysqli_query($db_connection, $query);
    $rowcount = mysqli_num_rows($result);
    if ($rowcount > 0) {
        $row = mysqli_fetch_assoc($result);

    ?>

        <h1>Booking Details Update</h1>
        <h2><a href='listbookings.php'>[Return to the bookings listing]</a><a href='index.php'>[Return to the main page]</a></h2>

        <form method="POST" action="editbookings.php">

            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <p>
                <label for="roomchoice">Room (name, type, beds):</label>
                <select name="roomchoice" id="roomchoice" required>
                    <option value="">Select a room</option>
                    <?php
                    $query = "SELECT roomID, roomname, roomtype, beds FROM room ORDER BY roomID";
                    $result = mysqli_query($db_connection, $query);
                    $rowcount = mysqli_num_rows($result);
                    if ($rowcount > 0) {
                        while ($roww = mysqli_fetch_assoc($result)) {
                            $id = $roww['roomchoice'];
                            echo '<option value "">' . $roww['roomname'] . ' (' . $roww['roomtype'] . ', ' . $roww['beds'] . ' beds)</option>';
                        }
                    } else {
                        echo "<h2>No rooms found!</h2> "; //suitable feedback
                    }
                    mysqli_free_result($result);
                    ?>
                </select>
            </p>
            <p>
                <label for="firstname">First name:</label>
                <input type="text" name="firstname" id="firstname" value="<?php echo $row['firstname']; ?>" required>
            </p>
            <p>
                <label for="lastname">Last name:</label>
                <input type="text" name="lastname" id="lastname" value="<?php echo $row['lastname']; ?>" required>
            </p>
            <p>
                <label for="checkin">Check in date:</label>
                <input type="date" name="checkin" id="checkin" value="<?php echo $row['checkin']; ?>" required>
            </p>
            <p>
                <label for="checkout">Check out date:</label>
                <input type="date" name="checkout" id="checkout" value="<?php echo $row['checkout']; ?>" required>
            </p>
            <p>
                <label for=contact> Contact number:</label>
                <input type="phone" name="contact" id="contact" value="<?php echo $row['contact']; ?>" required>
            </p>
            <p>
                <label for="extra">Booking extras:</label>
                <input type="text" name="extra" id="extra" value="<?php echo $row['extra']; ?>">
            </p>
            <p>
                <label for="review">Review:</label>
                <input type="text" name="review" id="review" value="<?php echo $row['review']; ?>">
            </p>
            <input type="submit" name="submit" value="Update">
        </form>
    <?php
    } else {
        echo "<h2>room not found with that ID</h2>"; //simple error feedback
    }
    mysqli_close($db_connection); //close the connection once done
    ?>
</body>

</html>