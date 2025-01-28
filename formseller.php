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

 
<p><input type="radio" name="seller" value="new" <?php if (isset($_POST['seller']) &&
($_POST['seller'] == 'new') ) echo ' checked="checked"'; ?>/>   New =>
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



