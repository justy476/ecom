<?php # Script 17.5 - ecombrowse_items.php
 // This page displays the available prints (products).

 // Set the page title and include the HTML header:
 $page_title = 'Browse the Items';
  include ('includes/header.html');
  

 require_once ('../../ecommysqli_connect.php');


 if (isset($_POST['submit-search'] )){
    
    $search= mysqli_real_escape_string($dbc, $_POST['search']);
    $q = "SELECT item_id, item_name, description FROM items WHERE item_name ='$search' OR description = '$search'  ORDER BY items.item_name ASC";  

 


     // Are we looking at a particular artist?
if (isset($_GET['aid']) && is_numeric($_GET['aid']) ) { $aid = (int) $_GET['aid'];
    if (($aid > 0)) { // Overwrite the query:
        $q = "SELECT item_id, item_name, description FROM items WHERE item_name ='$search' OR description = '$search'  ORDER BY items.item_name ASC"; 
}
        
        
    }
    
     // Create the table head:
        
     echo '<table border="0" width="90%" cellspacing="3" cellpadding="3" align="center">
    
    
    <tr>
   
     <td align="left" width="20%"><b>Item Name</b></td>
     <td align="left" width="40%"><b>Description</b></td>';
    
     // Display all the prints, linked to URLs:
     $r = mysqli_query ($dbc, $q);
     $queryResults= mysqli_num_rows ($r);

     if( $queryResults>0) {
     while ($row = mysqli_fetch_assoc ($r)) {
    
    // Display each record:
     echo "\t
     
     <tr>
     
     <td align=\"left\"><a href=\"ecomview_items.php?iid={$row ['item_id']}\">{$row['item_name']}</td>
     <td align=\"left\">{$row['description']}</td> 
     \n";
     }
 
 

 
    


    echo '</table>';
    mysqli_close($dbc);
   }
}  



 include ('includes/footer.html');

?>