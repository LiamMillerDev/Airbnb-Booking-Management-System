<!DOCTYPE HTML>
<html><head><title>MySQL connection test</title> </head>
<body>
<?php
// access the database constants
    include "config.php";  
// connect to the database using the constants
    $db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);
 
// check if the connection was good
    if (!$db_connection)
    {
        echo "Error: Unable to connect to MySQL ". mysqli_connect_errno()."= ".mysqli_connect_error();
//stop processing the page further
        exit;  
    };
 
// confirm that you have a connection by echoing the host name
    echo "Connected to ".mysqli_get_host_info($db_connection); 
 
// add the code that interacts with the database here
 
// close the connection once done    
    mysqli_close($db_connection); 
?>
</body>
</html>