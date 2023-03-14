<!DOCTYPE HTML>
<html>

<head>
  <title>Make a booking</title>
</head>

<body>
  <h1>Make a booking</h1>
  <h2><a href='listbookings.php'>[Return to the bookings listing]</a><a href='index.php'>[Return to the main page]</a></h2>
  <?php
  include "config.php"; //load in any variables
  include "cleaninput.php";
  include "checksession.php";
  checkuser();
  $db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);

  //the data was sent using a form therefore we use the $_POST instead of $_GET
  //check if we are saving data first by checking if the submit button exists in the array
  if (isset($_POST['submit']) and !empty($_POST['submit']) and ($_POST['submit'] == 'Register')) {
    //if ($_SERVER["REQUEST_METHOD"] == "POST") { //alternative simpler POST test    
    $error = 0; //clear our error flag
    $msg = 'Error: ';
    if (mysqli_connect_errno()) {
      echo "Error: Unable to connect to MySQL. " . mysqli_connect_error();
      exit; //stop processing the page further
    };
    $roomchoice = cleanInput($_POST['roomchoice']);
    $firstname = cleanInput($_POST['firstname']);
    $lastname = cleanInput($_POST['lastname']);
    $checkin = cleanInput($_POST['checkin']);
    $checkout = cleanInput($_POST['checkout']);
    $contact = cleanInput($_POST['contact']);
    $extra = cleanInput($_POST['extra']);



    if ($error == 0) {
      $query = "INSERT INTO booking (roomchoice, firstname, lastname, checkin, checkout, contact, extra) VALUES (?,?,?,?,?,?,?)";
      $stmt = mysqli_prepare($db_connection, $query); //prepare the query
      mysqli_stmt_bind_param($stmt, 'ssssssb', $roomchoice, $firstname, $lastname, $checkin, $checkout, $contact, $extra);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_close($stmt);
      echo "<h2>Booking made</h2>";
    } else {
      echo "<h2>$msg</h2>";
    }
    mysqli_close($db_connection); //close the connection once done
  }
  ?>
  <form method="POST" action="booking.php">
    <p>
      <label for="roomchoice">Room (name, type, beds):</label>
      <select name="roomchoice" id="roomchoice" required>
        <option value="">Select a room</option>

        <?php
        include "config.php"; //load in any variables
        $db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE) or die();
        $query = "SELECT roomID, roomname, roomtype, beds FROM room ORDER BY roomID";
        $result = mysqli_query($db_connection, $query);
        $rowcount = mysqli_num_rows($result);
        if ($rowcount > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['roomchoice'];
            echo '<option value "">' . $row['roomname'] . ' (' . $row['roomtype'] . ', ' . $row['beds'] . ' beds)</option>';
          }
        } else {
          echo "<h2>No rooms found!</h2> "; //suitable feedback
        }
        mysqli_free_result($result);
        mysqli_close($db_connection);
        ?>
      </select>

    </p>

    <p>
      <label for="firstname">First name:</label>
      <input type="text" name="firstname" id="firstname" required>
    </p>
    <p>
      <label for="lastname">Last name:</label>
      <input type="text" name="lastname" id="lastname" required>
    </p>
    <p>
      <label for="checkin">Check in date:</label>
      <input type="date" name="checkin" id="checkin" min="" required>
    </p>
    <p>
      <label for="checkout">Check out date:</label>
      <input type="date" name="checkout" id="checkout" required>
    </p>
    <p>
      <label for=contact> Contact number:</label>
      <input type="phone" name="contact" id="contact" required>
    </p>
    <p>
      <label for="extra">Booking extras:</label>
      <input type="text" name="extra" id="extra">
    </p>

    <input type="submit" name="submit" value="Register">

  </form>
</body>

</html>