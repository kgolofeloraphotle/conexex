<?php
// Include config file
require_once "config.php";
// Define variables and initialize with empty values
$id = $price = $description = $stock = $status = $name = "" ;
$id_err = $price_err = $description_err = $stock_err = $status_err = $name_err  = "";
// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
// Get hidden input value
$id = $_POST["id"];
// Validate id
$input_id = trim($_POST["id"]);
if(empty($input_id)){
$id_err = "Please enter an id.";
} else{
$id = $input_id;
}
// Validate salary
$input_price = trim($_POST["price"]);
if(empty($input_price)){
$price_err = "Please enter the price amount.";
} elseif(!ctype_digit($input_price)){
$price_err = "Please enter a positive integer value.";
} else{
$price = $input_price;
}


// Validate address address
$input_description = trim($_POST["description"]);
if(empty($input_description)){
$description_err = "Please enter an description.";
} else{
$description = $input_description;
}
// Validate address address
$input_stock = trim($_POST["stock"]);
if(empty($input_stock)){
$stock_err = "Please enter an stock.";
} else{
$stock = $input_stock;
}
// Validate address address
$input_status = trim($_POST["status"]);
if(empty($input_status)){
$status_err = "Please enter an status.";
} else{
$status = $input_status;
}
// Validate name
$input_name = trim($_POST["name"]);
if(empty($input_name)){
    $name_err = "Please enter a name.";
} elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP,
array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
$name_err = "Please enter a valid name.";
} else{
$name = $input_name;
}
// Validate image
if(isset($_FILES['pp']['name']) AND !empty($_FILES['pp']['name'])) {

    $img_name = $_FILES['pp']['name'];
    $tmp_name = $_FILES['pp']['tmp_name'];
    $error = $_FILES['pp']['error'];

    If($error === 0){
        $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
        $img_ex_to_lc = strtolower($img_ex);

        $allowed_exs = array('jpg', 'jpeg', 'png');
        if(in_array($img_ex_to_lc, $allowed_exs)){

            $new_img_name = uniqid($name, true).'.'.$img_ex_to_lc;
            $img_upload_path = '../images/'.$new_img_name;
            move_uploaded_file($tmp_name, $img_upload_path); 
        }
    }
}


// Check input errors before inserting in database
if(empty($id_err) &&  empty($price_err) && empty($description_err) && empty($stock_err) && empty($status_err) && empty($name_err) && empty($image_err)){
// Prepare an update statement
$sql = "UPDATE products SET price=?, description=?, stock=?, status=?, name=?, image=? WHERE id=?";

if($stmt = mysqli_prepare($conn, $sql)){
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "ssssssi",$param_id, $param_price, $param_description, $param_stock, $param_status, $param_name, $param_image);
    
    // Set parameters
    $param_id = $id;
    $param_price = $price;
    $param_description = $description;
    $param_stock = $stock;
    $param_status = $status;
    $param_name = $name;
    $param_image = $image;
    
    
    // Attempt to execute the prepared statement
    if(mysqli_stmt_execute($stmt)){
    // Records updated successfully. Redirect to landing page
    header("location: admin.php");
    exit();
    } else{
    echo "Oops! Something went wrong. Please try again later.";
    }
    }
    // Close statement
    mysqli_stmt_close($stmt);
    }

    // Close connection
mysqli_close($conn);
} else{
// Check existence of id parameter before processing further
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
// Get URL parameter
$id = trim($_GET["id"]);
// Prepare a select statement
$sql = "SELECT * FROM products WHERE id = ?";
if($stmt = mysqli_prepare($conn, $sql)){
// Bind variables to the prepared statement as parameters
mysqli_stmt_bind_param($stmt, "i", $param_id);
// Set parameters
$param_id = $id;
// Attempt to execute the prepared statement
if(mysqli_stmt_execute($stmt)){
$result = mysqli_stmt_get_result($stmt);



if(mysqli_num_rows($result) == 1){
/* Fetch result row as an associative array. Since the result set
contains only one row, we don't need to use while loop*/

$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
// Retrieve individual field value
$id = $row["id"];
$price = $row["price"];
$description = $row["description"];
$stock = $row["stock"];
$status = $row["status"];
$name = $row["name"];
$image = $row["image"];

} else{
    // URL doesn't contain valid id. Redirect to error page
header("location: error.php");
exit();
}
} else{
echo "Oops! Something went wrong. Please try again later.";
}
}
// Close statement
mysqli_stmt_close($stmt);
// Close connection
mysqli_close($conn);
} else{
// URL doesn't contain id parameter. Redirect to error page
header("location: error.php");
exit();
}
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Update Record</title>
 <conn rel="stylesheet"href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<style>
   .wrapper{
       width: 600px;
       margin: 0 auto;
    }
</style>
</head>
<body>
<div class="wrapper">
<div class="container-fluid">
<div class="row">
<div class="col-md-12">
<h2 class="mt-5">Update Record</h2>
<p>Please edit the input values and submit to update the product record.</p>

<form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
<div class="form-group">
<label>id</label>
<input type="text" name="id" class="form-control <?php echo (!empty($id_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $id; ?>">
<span class="invalid-feedback"><?php echo $id_err;?></span>
</div>
<div class="form-group">
<label>price</label>
<input type="text" name="price" class="form-control <?php echo (!empty($price_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $price; ?>">
<span class="invalid-feedback"><?php echo $price_err;?></span>
</div>
<div class="form-group">
<label>Description</label>
<textarea name="description" class="form-control
<?php echo (!empty($description_err)) ? 'is-invalid' : ''; ?>"><?php echo $description; ?></textarea>
<span class="invalid-feedback"><?php echo $description_err;?></span>
</div>
<div class="form-group">
<label>stock</label>
<input type="text" name="stock" class="form-control <?php echo (!empty($stock_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $stock; ?>">
<span class="invalid-feedback"><?php echo $stock_err;?></span>
</div>
<div class="form-group">
<label>status</label>
<input type="text" name="status" class="form-control <?php echo (!empty($status_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $status; ?>">
<span class="invalid-feedback"><?php echo

$status_err;?></span>
</div>
<div class="form-group">
<label>Name</label>
<input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>"
value="<?php echo $name; ?>">
<span class="invalid-feedback"><?php echo $name_err;?></span></div>

<div class="form-group">
<label>Image</label>
<input type="file" name="image" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>"
value="<?php echo $image; ?>">
</div>
<input type="hidden" name="id" value="<?php echo $id; ?>"/>
<input type="submit" class="btn btn-primary" value="Submit">
<a href="admin.php" class="btn btn-secondary ml-2">Cancel</a>
</form>
</div>
</div>
</div>
</div>
</body>
</html>