<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
 <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
 <head>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<title>Add an item</title>
</head>
<body>
<?php # Script 17.1 - ecomadditem.php
 // This page allows the administrator to add a print (product).

 require_once ('includes/ecommysqli_connect.php');
 

 if (isset($_POST['submitted'])) { // Handle the form.

// Validate the incoming data...
 $errors = array();

 // Check for a print name:
 if (!empty($_POST['item_name'])) {
 $in = trim($_POST['item_name']);
 } else {
$errors[] = 'Please enter the items\'s name!';
 }

// Check for an image:
 if (is_uploaded_file ($_FILES['image']['tmp_name'])) {

 // Create a temporary file name:
$temp = 'includes/ecomuploads/' . md5($_FILES['image']['name']);

// Move the file over:
 if (move_uploaded_file($_FILES['image']['tmp_name'], $temp)) {

 echo '<p>The file has been uploaded!</p>';



// Set the $i variable to the image’s name:
$i = $_FILES['image']['name'];

} else { // Couldn’t move the file over.
 $errors[] = 'The file could not be moved.';
 $temp = $_FILES['image']['tmp_name'];
 }

 } else { // No uploaded file.
 $errors[] = 'No file was uploaded.';
$temp = NULL;
 }

 // Check for a size (not required):
 $s = (!empty($_POST['size'])) ? trim($_POST['size']) : NULL;

 // Check for a price:
 if (is_numeric($_POST['price'])) {
$p = (float) $_POST['price'];
} else {
$errors[] = 'Please enter the item\'s price!';
}

// Check for a description (not required):
 $d = (!empty($_POST['description'])) ? trim($_POST['description']) : NULL;

 // Validate the artist...
 if (isset($_POST['seller']) && ($_POST['seller'] == 'new') ) {
// If it’s a new artist, add the artist to the database...

 // Validate the first and middle names (neither required):
 $fn = (!empty($_POST['first_name'])) ? trim($_POST['first_name']) : NULL;
 $mn = (!empty($_POST['middle_name'])) ? trim($_POST['middle_name']) : NULL;

 // Check for a last_name...
 if (!empty($_POST['last_name'])) {



    $ln = trim($_POST['last_name']);
    
     // Add the artist to the database:
     $q = 'INSERT INTO sellers (first_name, middle_name, last_name) VALUES (?, ?, ?)';
     $stmt = mysqli_prepare($dbc, $q);
     mysqli_stmt_bind_param($stmt, 'sss', $fn, $mn, $ln);
     mysqli_stmt_execute($stmt);
    
    // Check the results....
     if (mysqli_stmt_affected_rows($stmt) == 1) {
     echo '<p>The artist has been added.</p>';
     $a = mysqli_stmt_insert_id($stmt); // Get the artist ID.
     } else { // Error!
     $errors[] = 'The new seller could not be added to the database!';
     }
    
     // Close this prepared statement:
     mysqli_stmt_close($stmt);
    
     } else { // No last name value.
     $errors[] = 'Please enter the seller\'s last name!';
     }
    
     } elseif ( isset($_POST['seller']) && ($_POST['seller'] == 'existing') && ($_POST['existing'] >0) ) { // Existing artist.
     $a = (int) $_POST['existing'];
    } else { // No seller selected.
     $errors[] = 'Please enter or select the item\'s seller!';
     }
    
     if (empty($errors)) { // If everything’s OK.
    
    // Add the print to the database:
     $q = 'INSERT INTO items (seller_id, item_name, price, size, description, image_name) VALUES
    (?, ?, ?, ?, ?, ?)';
     $stmt = mysqli_prepare($dbc, $q);
    
    

    mysqli_stmt_bind_param($stmt, 'isdsss', $a, $in, $p, $s, $d, $i);
 mysqli_stmt_execute($stmt);

// Check the results...
 if (mysqli_stmt_affected_rows($stmt) == 1) {
 // Print a message:
 echo '<p>The item has been added.</p>';

 // Rename the image:
 $id = mysqli_stmt_insert_id($stmt); // Get the print ID.
 rename ($temp, "../../ecomuploads/$id");

 // Clear $_POST:
 $_POST = array();

 } else { // Error!
 echo '<p style="font-weight: bold; color: #C00">Your submission could not be processed due to a system error.</p>';
}

 mysqli_stmt_close($stmt);

 } // End of $errors IF.

 // Delete the uploaded file if it still exists:
 if ( isset($temp) && file_exists ($temp) && is_file($temp) ) {
 unlink ($temp);
 }

} // End of the submission IF.

 // Check for any errors and print them:
 if ( !empty($errors) && is_array($errors) ) {
 echo '<h1>Error!</h1>
 <p style="font-weight: bold; color: #C00">The following error(s) occurred:<br />';
 foreach ($errors as $msg) {



    echo " - $msg<br />\n";
     }
     echo 'Please reselect the item image and try again.</p>';
     }
    
    // Display the form...
    ?>
    <h1>Add an Item</h1>
    <form enctype="multipart/form-data" action="ecom17additem.php" method="post">
    
     <input type="hidden" name="MAX_FILE_SIZE" value="524288" />
    
     <fieldset><legend>Fill out the form to add an item to the catalog:</legend>

 <p><b>Item Name:</b> <input type="text" name="item_name" size="30" maxlength="60"
    value="<?php if (isset($_POST['item_name'])) echo htmlspecialchars($_POST['item_name']); ?>"/></p>
    
     <p><b>Image:</b> <input type="file" name="image" /></p>
    
     <div><b>Seller:</b>
     <p><input type="radio" name="seller" value="existing" <?php if (isset($_POST['seller']) &&
    ($_POST['seller'] == 'existing') ) echo 'checked="checked"'; ?>/> Existing =>
     <select name="existing"><option>Select One</option>

 
     
    <?php // Retrieve all the sellers and add to the pull-down menu.
     $q = "SELECT seller_id, CONCAT_WS(' ', first_name, middle_name, last_name) FROM sellers ORDER BY last_name, first_name ASC";
     $r = mysqli_query ($dbc, $q);
     if (mysqli_num_rows($r) > 0) { while ($row = mysqli_fetch_array ($r, MYSQLI_NUM)) {
    echo "<option value=\"$row[0]\"";
    
     // Check for stickyness:
     if (isset($_POST['existing']) && ($_POST['existing'] == $row[0]) ) echo 'selected="selected"';
    echo ">$row[1]</option>\n";
     }
     } else {
     echo '<option>Please add a new seller.</option>';
    
    


}
 mysqli_close($dbc); // Close the database connection.
?>
</select></p>

 
<p><input type="radio" name="seller" value="new" <?php if (isset($_POST['seller']) && ($_POST['seller'] == 'new') ) echo ' checked="checked"'; ?>/>   New =>
 First Name: <input type="text" name="first_name" size="10" maxlength="20" value="<?php if (isset($_POST['first_name'])) echo $_POST['first_name']; ?>" />
 Middle Name: <input type="text" name="middle_name" size="10" maxlength="20" value="<?php if (isset($_POST['middle_name'])) echo $_POST['middle_name']; ?>" />
 Last Name: <input type="text" name="last_name" size="10" maxlength="40" value="<?php if (isset($_POST['last_name'])) echo $_POST['last_name']; ?>" /></p>
 </div>
 





 <p><b>Price:</b> <input type="text" name="price" size="10" maxlength="10" value="<?php if
(isset($_POST['price'])) echo $_POST['price']; ?>" /> <small>Do not include the dollar sign or commas.</small></p>

 <p><b>Size:</b> <input type="text" name="size" size="30" maxlength="60" value="<?php if
(isset($_POST['size'])) echo htmlspecialchars($_POST['size']); ?>" /> (optional)</p>

<p><b>Description:</b> <textarea name="description" cols="40" rows="5"><?php if
(isset($_POST['description'])) echo $_POST['description']; ?></textarea> (optional)</p>

</fieldset>

 <div align="center"><input type="submit" name="submit" value="Submit" /></div>
 <input type="hidden" name="submitted" value="TRUE" />

 </form>

 </body>
 </html>


