<!DOCTYPE HTML>
<html><head><title>Browse bookings</title> </head>
<body>
<?php
include "checksession.php";
checkUser();
include "config.php"; //load in any variables
$db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE) or die();
$query = "SELECT bookingID, roomchoice, firstname, lastname, checkin, checkout FROM booking ORDER BY bookingID";
$result = mysqli_query($db_connection, $query);
$rowcount = mysqli_num_rows($result); 
?>

<h1>Current bookings</h1>

<h2><a href='booking.php'>[Make a booking]</a><a href="index.php">[Return to main page]</a>
</h2>

<table id="tblrooms" border="1">
<thead><tr><th>Booking (room, dates) </th><th>Customer</th><th>Action</th></tr></thead>

<?php
// check that you have members retrieved
if ($rowcount > 0)
{  
 
    while ($row = mysqli_fetch_assoc($result)) {
        $id = $row['bookingID'];    
        echo '<tr><td>'.$row['roomchoice'];
        echo ' ('.$row['checkin'].' to '.$row['checkout'].')'.'</td>';
        echo '<td>'.$row['lastname'] ;
        echo ', '.$row['firstname'].'</td>';
        echo '<td><a href= "viewbookings.php?id='.$id.' ">[view]</a>';
        echo '<a href= "editbookings.php?id='.$id.' ">[edit]</a>';
        echo '<a href= "managereviews.php?id='.$id.' ">[manage reviews]</a>';
        echo '<a href= "deletebookings.php?id='.$id.' ">[delete]</a></td>';
        echo '</tr>';
   }
} 
else
{
    echo "<h2>No bookings found!</h2> "; //suitable feedback
}
mysqli_free_result($result); 
mysqli_close($db_connection);
?>

</table>
</body>
</html>