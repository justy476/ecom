<?php # Script 16.4 - mysqli_connect.php

 // This file contains the database access information.
// This file also establishes a connection to MySQL
 // and selects the database.

// Set the database access information as
constants:
DEFINE ('DB_USER', 'ocodwovlwk');
DEFINE ('DB_PASSWORD', 'Splash4real');
 DEFINE ('DB_HOST', 'splasheventser.mysql.database.azure.com');
 DEFINE ('DB_NAME', 'splasheventserdata');

 // Make the connection:
 $dbc = @mysqli_connect (DB_HOST, DB_USER,
DB_PASSWORD, DB_NAME);

 if (!$dbc) {
 trigger_error ('Could not connect to MySQL:'.mysqli_connect_erro());
 }

 ?>
