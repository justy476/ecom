<?php # Script 17.5 - ecombrowse_items.php
 // This page displays the available prints (products).

 // Set the page title and include the HTML header:
 $page_title = 'Browse the Items';
  include ('includes/header.html');
 require_once ('../../ecommysqli_connect.php');
?>
 
 <form action = "ecomsearchseller.php" method= "POST">  <input type="text" name="search" placeholder = "search">
 <button type="submit" name="submit-search">search seller</button>
 </form>

 <form action = "ecomsearchitem.php" method= "POST">  <input type="text" name="search" placeholder = "search">
 <button type="submit" name="submit-search">search item</button>

</form>
 
  

<?php

 // Default query for this page:
 $q = "SELECT sellers.seller_id, CONCAT_WS(' ', first_name, middle_name, last_name) AS seller, item_name, price, description, item_id FROM sellers, items WHERE sellers.seller_id = items.seller_id ORDER BY sellers.last_name ASC, items.item_name ASC";

 // Are we looking at a particular artist?
if (isset($_GET['aid']) && is_numeric($_GET['aid']) ) { $aid = (int) $_GET['aid'];
if ($aid > 0) { // Overwrite the query:
 $q = "SELECT sellers.seller_id, CONCAT_WS(' ', first_name, middle_name, last_name) AS seller, item_name, price, description, item_id FROM sellers, items WHERE sellers.seller_id = items.seller_id AND items.seller_id = $aid ORDER BY items.item_name";
 }
 }

 // Create the table head:
 echo '<table border="0" width="90%" cellspacing="3" cellpadding="3" align="center">


<tr>
 <td align="left"
width="20%"><b>Seller</b></td>
 <td align="left" width="20%"><b>Item Name</b></td>
 <td align="left" width="40%"><b>Description</b></td>
 <td align="right" width="20%"><b>Price</b></td> </tr>';

 // Display all the prints, linked to URLs:
 $r = mysqli_query ($dbc, $q);
 while ($row = mysqli_fetch_array ($r, MYSQLI_ASSOC)) {

// Display each record:
 echo "\t
 
 <tr>
 <td align=\"left\"><a href=\"ecombrowse_items.php?aid={$row ['seller_id']}\">{$row['seller']}</a></td>
 <td align=\"left\"><a href=\"ecomview_items.php?iid={$row ['item_id']}\">{$row['item_name']}</td>
 <td align=\"left\">{$row['description']}</td> 
 <td align=\"right\">\${$row['price']}</td> </tr>\n";

} // End of while loop.

 echo '</table>';
 mysqli_close($dbc);
 include ('includes/footer.html');
  ?>