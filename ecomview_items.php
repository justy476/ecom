<?php # Script 17.6 - ecomview_print.php
 // This page displays the details for a particular print.

 $row = FALSE; // Assume nothing!

 if (isset($_GET['iid']) && is_numeric($_GET['iid']) ) { // Make sure there's a print ID!

$iid = (int) $_GET['iid'];

 // Get the print info:
 require_once ('../../ecommysqli_connect.php');
  
 $q = "SELECT CONCAT_WS(' ', first_name, middle_name, last_name) AS seller, item_name, price, description, size, image_name FROM sellers, items WHERE sellers.seller_id = items.seller_id AND items.item_id = $iid";
 
 $r = mysqli_query ($dbc, $q);
 if (mysqli_num_rows($r) == 1) { // Good to go!

 // Fetch the information:
 $row = mysqli_fetch_array ($r,MYSQLI_ASSOC);
 
// Start the HTML page:
 $page_title = $row['item_name'];
 include ('includes/header.html');

  


 // Display a header:
  echo "<div align=\"center\">
  <b>{$row['item_name']}</b> by {$row['seller']}<br />";
 
 // Print the size or a default message:
echo (is_null($row['size'])) ? '(No size information available)' :$row['size'];

echo "<br />\${$row['price']}
 <a href=\"ecomadd_cart.php?iid=$iid\">Add to Cart</a>
 </div><br />";


 


// Get the image information and display the image:
   if ($image = @getimagesize ("../../ecomuploads/$iid")) { echo "<div align=\"center\"><img src=\"ecomshow_image.php?image=$iid & name=" . urlencode($row['image_name']) . "\" $image[3] alt=\"{$row['item_name']}\"/></div>\n";
    } else {
   echo "<div align=\"center\">No image available.</div>\n";
    }

 // Add the description or a default message:
 echo '<p align="center">' . ((is_null($row['description'])) ? '(No description available)' : $row['description']) . '</p>';

 } // End of the mysqli_num_rows() IF.

 mysqli_close($dbc);




    }
 if (!$row) { // Show an error message.
 $page_title = 'Error';
include ('includes/header.html');
 echo '<div align="center">This page has been accessed in error!</div>';
 }

// Complete the page:
 include ('includes/footer.html');
 ?>

