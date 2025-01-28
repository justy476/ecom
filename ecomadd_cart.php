<?php # Script 17.8 - add_cart.php
 // This page adds prints to the shopping cart.

 // Set the page title and include the HTML header:
 $page_title = 'Add to Cart';
include ('includes/header.html');

if (isset ($_GET['iid']) && is_numeric($_GET['iid']) ) { // Check for a print ID.

 $iid = (int) $_GET['iid'];

// Check if the cart already contains one of these prints;
// If so, increment the quantity:
 if (isset($_SESSION['cart'][$iid])) {

$_SESSION['cart'][$iid]['quantity'] ++; // Add another. 


// Display a message.
 echo '<p>Another copy of the item has been added to your shopping cart.</p>';

 } else { // New product to the cart.

 // Get the print's price from the database:
require_once ('../../ecommysqli_connect.php');
 $q = "SELECT price FROM items WHERE items.item_id = $iid";
  $r = mysqli_query ($dbc, $q);
if (mysqli_num_rows($r) == 1) {
// Valid print ID.
 // Fetch the information.
 list($price) = mysqli_fetch_array($r, MYSQLI_NUM);

 // Add to the cart:
 $_SESSION['cart'][$iid] = array ('quantity' => 1, 'price' => $price);

// Display a message:
 echo '<p>The item has been added to your shopping cart.</p>';

} else { // Not a valid print ID.
 echo '<div align="center">This page has been accessed in error!</div>';
 }

 mysqli_close($dbc);



} // End of isset($_SESSION['cart'][$iid] conditional.

 } else { // No print ID.
echo '<div align="center">This page has been accessed in error!</div>';
 }

 include ('includes/footer.html');
 ?>