<?php
// Include config file
include_once('functions-page.php');
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
$sql = "SELECT * FROM userform WHERE id = ?";
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
    <meta http-equiv="x-ua-compatible" content="ie=edge">
<title>Update Record</title>
 <conn rel="stylesheet"href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<style>
   .wrapper{
       width: 0 auto;
       margin: 0 auto;
    }
</style>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" ></script>
    <link rel="font" rel="preload" as="font" type="font/woff2" crossorigin href="https://connexstore.co.za/themes/AngarTheme/assets/css/fonts/material_icons.woff2" />
    <link rel="font" rel="preload" as="font" type="font/woff2" crossorigin href="https://connexstore.co.za/themes/AngarTheme/assets/css/fonts/fontawesome-webfont.woff2?v=4.7.0" />
   
    <link rel="stylesheet" rel="preload" as="style" href="https://connexstore.co.za/themes/AngarTheme/assets/css/theme.css" media="all" />
    <link rel="stylesheet" rel="preload" as="style" href="https://connexstore.co.za/themes/AngarTheme/assets/css/libs/jquery.bxslider.css" media="all" />
    <link rel="stylesheet" rel="preload" as="style" href="https://connexstore.co.za/themes/AngarTheme/assets/css/font-awesome.css" media="all" />
    <link rel="stylesheet" rel="preload" as="style" href="https://connexstore.co.za/themes/AngarTheme/assets/css/home_modyficators.css" media="all" />
    <link rel="stylesheet" rel="preload" as="style" href="https://connexstore.co.za/themes/AngarTheme/assets/css/rwd.css" media="all" />
    <link rel="stylesheet" rel="preload" as="style" href="https://connexstore.co.za/themes/AngarTheme/assets/css/black.css" media="all" />
    <link rel="stylesheet" rel="preload" as="style" href="https://connexstore.co.za/modules/blockreassurance/views/dist/front.css" media="all" />
    <link rel="stylesheet" rel="preload" as="style" href="https://connexstore.co.za/themes/AngarTheme/modules/ps_searchbar/ps_searchbar.css" media="all" />
    <link rel="stylesheet" rel="preload" as="style" href="https://connexstore.co.za/modules/productcomments/views/css/productcomments.css" media="all" />
    <link rel="stylesheet" rel="preload" as="style" href="https://connexstore.co.za/modules/angarbanners/views/css/hooks.css" media="all" />
    <link rel="stylesheet" rel="preload" as="style" href="https://connexstore.co.za/modules/angarcatproduct/views/css/at_catproduct.css" media="all" />
    <link rel="stylesheet" rel="preload" as="style" href="https://connexstore.co.za/modules/angarcmsinfo/views/css/angarcmsinfo.css" media="all" />
    <link rel="stylesheet" rel="preload" as="style" href="https://connexstore.co.za/modules/angarfacebook/views/css/angarfacebook.css" media="all" />
    <link rel="stylesheet" rel="preload" as="style" href="https://connexstore.co.za/modules/angarhomecat/views/css/at_homecat.css" media="all" />
    <link rel="stylesheet" rel="preload" as="style" href="https://connexstore.co.za/modules/angarslider/views/css/angarslider.css" media="all" />
    <link rel="stylesheet" rel="preload" as="style" href="https://connexstore.co.za/modules/angarscrolltop/views/css/angarscrolltop.css" media="all" />
    <link rel="stylesheet" rel="preload" as="style" href="https://connexstore.co.za/modules/prestanotifypro/views/css/shadowbox/shadowbox.css" media="all" />
    <link rel="stylesheet" rel="preload" as="style" href="https://connexstore.co.za/modules/ets_banneranywhere/views/css/front.css" media="all" />
    <link rel="stylesheet" rel="preload" as="style" href="https://connexstore.co.za/modules/roja45quotationspro/views/css/roja45quotationspro17.css" media="all" />
    <link rel="stylesheet" rel="preload" as="style" href="https://connexstore.co.za/modules/ybc_productimagehover/views/css/fix17.css" media="all" />
    <link rel="stylesheet" rel="preload" as="style" href="https://connexstore.co.za/modules/nkmdeliverydate//views/css/front.css" media="all" />
    <link rel="stylesheet" rel="preload" as="style" href="https://connexstore.co.za/modules/responsive/views/css/fluid.css" media="all" />
    <link rel="stylesheet" rel="preload" as="style" href="https://connexstore.co.za/modules/carrieronorder//views/css/front.css" media="all" />
    <link rel="stylesheet" rel="preload" as="style" href="https://connexstore.co.za/modules/codwfeeplus/views/css/style-front_17.css" media="all" />
    <link rel="stylesheet" rel="preload" as="style" href="https://connexstore.co.za/js/jquery/ui/themes/base/minified/jquery-ui.min.css" media="all" />
    <link rel="stylesheet" rel="preload" as="style" href="https://connexstore.co.za/js/jquery/ui/themes/base/minified/jquery.ui.theme.min.css" media="all" />
    <link rel="stylesheet" rel="preload" as="style" href="https://connexstore.co.za/themes/AngarTheme/modules/blockwishlist/public/wishlist.css" media="all" />
    <link rel="stylesheet" rel="preload" as="style" href="https://connexstore.co.za/js/jquery/plugins/fancybox/jquery.fancybox.css" media="all" />
    <link rel="stylesheet" rel="preload" as="style" href="https://connexstore.co.za/js/jquery/plugins/growl/jquery.growl.css" media="all" />
    <link rel="stylesheet" rel="preload" as="style" href="https://connexstore.co.za/themes/AngarTheme/assets/css/custom.css" media="all" />

    <link rel="stylesheet" href="https://connexstore.co.za/themes/AngarTheme/assets/css/theme.css" type="text/css" media="all">
    <link rel="stylesheet" href="https://connexstore.co.za/themes/AngarTheme/assets/css/libs/jquery.bxslider.css" type="text/css" media="all">
    <link rel="stylesheet" href="https://connexstore.co.za/themes/AngarTheme/assets/css/font-awesome.css" type="text/css" media="all">
    <link rel="stylesheet" href="https://connexstore.co.za/themes/AngarTheme/assets/css/angartheme.css" type="text/css" media="all">
    <link rel="stylesheet" href="https://connexstore.co.za/themes/AngarTheme/assets/css/home_modyficators.css" type="text/css" media="all">
    <link rel="stylesheet" href="https://connexstore.co.za/themes/AngarTheme/assets/css/rwd.css" type="text/css" media="all">
    <link rel="stylesheet" href="https://connexstore.co.za/themes/AngarTheme/assets/css/black.css" type="text/css" media="all">
    <link rel="stylesheet" href="https://connexstore.co.za/modules/blockreassurance/views/dist/front.css" type="text/css" media="all">
    <link rel="stylesheet" href="https://connexstore.co.za/themes/AngarTheme/modules/ps_searchbar/ps_searchbar.css" type="text/css" media="all">
    <link rel="stylesheet" href="https://connexstore.co.za/modules/productcomments/views/css/productcomments.css" type="text/css" media="all">
    <link rel="stylesheet" href="https://connexstore.co.za/modules/angarbanners/views/css/hooks.css" type="text/css" media="all">
    <link rel="stylesheet" href="https://connexstore.co.za/modules/angarcatproduct/views/css/at_catproduct.css" type="text/css" media="all">
    <link rel="stylesheet" href="https://connexstore.co.za/modules/angarcmsinfo/views/css/angarcmsinfo.css" type="text/css" media="all">
    <link rel="stylesheet" href="https://connexstore.co.za/modules/angarfacebook/views/css/angarfacebook.css" type="text/css" media="all">
    <link rel="stylesheet" href="https://connexstore.co.za/modules/angarhomecat/views/css/at_homecat.css" type="text/css" media="all">
    <link rel="stylesheet" href="https://connexstore.co.za/modules/angarslider/views/css/angarslider.css" type="text/css" media="all">
    <link rel="stylesheet" href="https://connexstore.co.za/modules/angarscrolltop/views/css/angarscrolltop.css" type="text/css" media="all">
    <link rel="stylesheet" href="https://connexstore.co.za/modules/prestanotifypro/views/css/shadowbox/shadowbox.css" type="text/css" media="all">
    <link rel="stylesheet" href="https://connexstore.co.za/modules/ets_banneranywhere/views/css/front.css" type="text/css" media="all">
    <link rel="stylesheet" href="https://connexstore.co.za/modules/roja45quotationspro/views/css/roja45quotationspro17.css" type="text/css" media="all">
    <link rel="stylesheet" href="https://connexstore.co.za/modules/ybc_productimagehover/views/css/fix17.css" type="text/css" media="all">
    <link rel="stylesheet" href="https://connexstore.co.za/modules/nkmdeliverydate//views/css/front.css" type="text/css" media="all">
    <link rel="stylesheet" href="https://connexstore.co.za/modules/responsive/views/css/fluid.css" type="text/css" media="all">
    <link rel="stylesheet" href="https://connexstore.co.za/modules/carrieronorder//views/css/front.css" type="text/css" media="all">
    <link rel="stylesheet" href="https://connexstore.co.za/modules/codwfeeplus/views/css/style-front_17.css" type="text/css" media="all">
    <link rel="stylesheet" href="https://connexstore.co.za/js/jquery/ui/themes/base/minified/jquery-ui.min.css" type="text/css" media="all">
    <link rel="stylesheet" href="https://connexstore.co.za/js/jquery/ui/themes/base/minified/jquery.ui.theme.min.css" type="text/css" media="all">
    <link rel="stylesheet" href="https://connexstore.co.za/themes/AngarTheme/modules/blockwishlist/public/wishlist.css" type="text/css" media="all">
    <link rel="stylesheet" href="https://connexstore.co.za/js/jquery/plugins/fancybox/jquery.fancybox.css" type="text/css" media="all">
    <link rel="stylesheet" href="https://connexstore.co.za/js/jquery/plugins/growl/jquery.growl.css" type="text/css" media="all">
    <link rel="stylesheet" href="https://connexstore.co.za/themes/AngarTheme/assets/css/custom.css" type="text/css" media="all">

    <link rel="stylesheet" href="css/style.css" type="text/css">
    <link rel="stylesheet" href="css/angartheme.css" type="text/css">
    <link rel="stylesheet" href="css/res-style.css" type="text/css">
    <link rel="stylesheet" rel="preload" as="style" href="https://fonts.googleapis.com/css?family=Poppins:400,600&amp;subset=latin,latin-ext&display=block" type="text/css" media="all" />

    <script type="text/javascript">
        var blockwishlistController = "https:\/\/connexstore.co.za\/module\/blockwishlist\/action";
        var codwfeeplus_codproductid = "4710";
        var codwfeeplus_codproductreference = "COD";
        var codwfeeplus_is17 = true;
        var prestashop = {
            "cart": {
                "products": [],
                "totals": {
                    "total": {
                        "type": "total",
                        "label": "Total",
                        "amount": 0,
                        "value": "R\u00a00.00"
                    },
                    "total_including_tax": {
                        "type": "total",
                        "label": "Total (tax incl.)",
                        "amount": 0,
                        "value": "R\u00a00.00"
                    },
                    "total_excluding_tax": {
                        "type": "total",
                        "label": "Total (tax excl.)",
                        "amount": 0,
                        "value": "R\u00a00.00"
                    }
                },
                "subtotals": {
                    "products": {
                        "type": "products",
                        "label": "Subtotal",
                        "amount": 0,
                        "value": "R\u00a00.00"
                    },
                    "discounts": null,
                    "shipping": {
                        "type": "shipping",
                        "label": "Shipping",
                        "amount": 0,
                        "value": ""
                    },
                    "tax": null
                },
                "products_count": 0,
                "summary_string": "0 items",
                "vouchers": {
                    "allowed": 0,
                    "added": []
                },
                "discounts": [],
                "minimalPurchase": 0,
                "minimalPurchaseRequired": ""
            },
            "currency": {
                "id": 2,
                "name": "South African Rand",
                "iso_code": "ZAR",
                "iso_code_num": "710",
                "sign": "R"
            },
            "customer": {
                "lastname": null,
                "firstname": null,
                "email": null,
                "birthday": null,
                "newsletter": null,
                "newsletter_date_add": null,
                "optin": null,
                "website": null,
                "company": null,
                "siret": null,
                "ape": null,
                "is_logged": false,
                "gender": {
                    "type": null,
                    "name": null
                },
                "addresses": []
            },
            "language": {
                "name": "English (English)",
                "iso_code": "en",
                "locale": "en-US",
                "language_code": "en-za",
                "is_rtl": "0",
                "date_format_lite": "m\/d\/Y",
                "date_format_full": "m\/d\/Y H:i:s",
                "id": 1
            },
            "page": {
                "title": "",
                "canonical": null,
                "meta": {
                    "title": "connexstore.co.za: shop online, save time and money.",
                    "description": "connex store, shop all things online.",
                    "keywords": "connex store, shop all things online.",
                    "robots": "index"
                },
                "page_name": "index",
                "body_classes": {
                    "lang-en": true,
                    "lang-rtl": false,
                    "country-ZA": true,
                    "currency-ZAR": true,
                    "layout-left-column": true,
                    "page-index": true,
                    "tax-display-enabled": true
                },
                "admin_notifications": []
            },
            "shop": {
                "name": "Connex Store",
                "logo": "https:\/\/connexstore.co.za\/img\/logo-1671096750.jpg",
                "stores_icon": "https:\/\/connexstore.co.za\/img\/logo_stores.png",
                "favicon": "https:\/\/connexstore.co.za\/img\/favicon.ico"
            },
            "urls": {
                "base_url": "https:\/\/connexstore.co.za\/",
                "current_url": "https:\/\/connexstore.co.za\/",
                "shop_domain_url": "https:\/\/connexstore.co.za",
                "img_ps_url": "https:\/\/connexstore.co.za\/img\/",
                "img_cat_url": "https:\/\/connexstore.co.za\/img\/c\/",
                "img_lang_url": "https:\/\/connexstore.co.za\/img\/l\/",
                "img_prod_url": "https:\/\/connexstore.co.za\/img\/p\/",
                "img_manu_url": "https:\/\/connexstore.co.za\/img\/m\/",
                "img_sup_url": "https:\/\/connexstore.co.za\/img\/su\/",
                "img_ship_url": "https:\/\/connexstore.co.za\/img\/s\/",
                "img_store_url": "https:\/\/connexstore.co.za\/img\/st\/",
                "img_col_url": "https:\/\/connexstore.co.za\/img\/co\/",
                "img_url": "https:\/\/connexstore.co.za\/themes\/AngarTheme\/assets\/img\/",
                "css_url": "https:\/\/connexstore.co.za\/themes\/AngarTheme\/assets\/css\/",
                "js_url": "https:\/\/connexstore.co.za\/themes\/AngarTheme\/assets\/js\/",
                "pic_url": "https:\/\/connexstore.co.za\/upload\/",
                "pages": {
                    "address": "https:\/\/connexstore.co.za\/address",
                    "addresses": "https:\/\/connexstore.co.za\/addresses",
                    "authentication": "https:\/\/connexstore.co.za\/login",
                    "cart": "https:\/\/connexstore.co.za\/cart",
                    "category": "https:\/\/connexstore.co.za\/index.php?controller=category",
                    "cms": "https:\/\/connexstore.co.za\/index.php?controller=cms",
                    "contact": "https:\/\/connexstore.co.za\/contact-us",
                    "discount": "https:\/\/connexstore.co.za\/discount",
                    "guest_tracking": "https:\/\/connexstore.co.za\/guest-tracking",
                    "history": "https:\/\/connexstore.co.za\/order-history",
                    "identity": "https:\/\/connexstore.co.za\/identity",
                    "index": "https:\/\/connexstore.co.za\/",
                    "my_account": "https:\/\/connexstore.co.za\/my-account",
                    "order_confirmation": "https:\/\/connexstore.co.za\/order-confirmation",
                    "order_detail": "https:\/\/connexstore.co.za\/index.php?controller=order-detail",
                    "order_follow": "https:\/\/connexstore.co.za\/order-follow",
                    "order": "https:\/\/connexstore.co.za\/order",
                    "order_return": "https:\/\/connexstore.co.za\/index.php?controller=order-return",
                    "order_slip": "https:\/\/connexstore.co.za\/credit-slip",
                    "pagenotfound": "https:\/\/connexstore.co.za\/page-not-found",
                    "password": "https:\/\/connexstore.co.za\/password-recovery",
                    "pdf_invoice": "https:\/\/connexstore.co.za\/index.php?controller=pdf-invoice",
                    "pdf_order_return": "https:\/\/connexstore.co.za\/index.php?controller=pdf-order-return",
                    "pdf_order_slip": "https:\/\/connexstore.co.za\/index.php?controller=pdf-order-slip",
                    "prices_drop": "https:\/\/connexstore.co.za\/prices-drop",
                    "product": "https:\/\/connexstore.co.za\/index.php?controller=product",
                    "search": "https:\/\/connexstore.co.za\/search",
                    "sitemap": "https:\/\/connexstore.co.za\/sitemap",
                    "stores": "https:\/\/connexstore.co.za\/stores",
                    "supplier": "https:\/\/connexstore.co.za\/supplier",
                    "register": "https:\/\/connexstore.co.za\/login?create_account=1",
                    "order_login": "https:\/\/connexstore.co.za\/order?login=1"
                },
                "alternative_langs": [],
                "theme_assets": "\/themes\/AngarTheme\/assets\/",
                "actions": {
                    "logout": "https:\/\/connexstore.co.za\/?mylogout="
                },
                "no_picture_image": {
                    "bySize": {
                        "small_default": {
                            "url": "https:\/\/connexstore.co.za\/img\/p\/en-default-small_default.jpg",
                            "width": 98,
                            "height": 98
                        },
                        "cart_default": {
                            "url": "https:\/\/connexstore.co.za\/img\/p\/en-default-cart_default.jpg",
                            "width": 125,
                            "height": 125
                        },
                        "home_default": {
                            "url": "https:\/\/connexstore.co.za\/img\/p\/en-default-home_default.jpg",
                            "width": 259,
                            "height": 259
                        },
                        "medium_default": {
                            "url": "https:\/\/connexstore.co.za\/img\/p\/en-default-medium_default.jpg",
                            "width": 400,
                            "height": 200
                        },
                        "large_default": {
                            "url": "https:\/\/connexstore.co.za\/img\/p\/en-default-large_default.jpg",
                            "width": 800,
                            "height": 400
                        }
                    },
                    "small": {
                        "url": "https:\/\/connexstore.co.za\/img\/p\/en-default-small_default.jpg",
                        "width": 98,
                        "height": 98
                    },
                    "medium": {
                        "url": "https:\/\/connexstore.co.za\/img\/p\/en-default-home_default.jpg",
                        "width": 259,
                        "height": 259
                    },
                    "large": {
                        "url": "https:\/\/connexstore.co.za\/img\/p\/en-default-large_default.jpg",
                        "width": 800,
                        "height": 400
                    },
                    "legend": ""
                }
            },
            "configuration": {
                "display_taxes_label": true,
                "display_prices_tax_incl": true,
                "is_catalog": false,
                "show_prices": true,
                "opt_in": {
                    "partner": false
                },
                "quantity_discount": {
                    "type": "price",
                    "label": "Unit price"
                },
                "voucher_enabled": 0,
                "return_enabled": 0
            },
            "field_required": [],
            "breadcrumb": {
                "links": [{
                    "title": "Home",
                    "url": "https:\/\/connexstore.co.za\/"
                }],
                "count": 1
            },
            "link": {
                "protocol_link": "https:\/\/",
                "protocol_content": "https:\/\/"
            },
            "time": 1674119152,
            "static_token": "3a6692178813dd428366c5889a7a5c84",
            "token": "7d38470dc3ead053a12a78899efd1f89",
            "debug": false
        };
        var productsAlreadyTagged = [];
        var psemailsubscription_subscription = "https:\/\/connexstore.co.za\/module\/ps_emailsubscription\/subscription";
        var psr_icon_color = "#F19D76";
        var removeFromWishlistUrl = "https:\/\/connexstore.co.za\/module\/blockwishlist\/action?action=deleteProductFromWishlist";
        var roja45_hide_add_to_cart = 0;
        var roja45_hide_price = 0;
        var roja45_quotation_useajax = 1;
        var roja45quotationspro_added_failed = "Unable to add product to your request.";
        var roja45quotationspro_added_success = "Product added to your request successfully.";
        var roja45quotationspro_allow_modifications = 0;
        var roja45quotationspro_button_addquote = "Add To Quote";
        var roja45quotationspro_button_text = "Request Quote";
        var roja45quotationspro_button_text_2 = "Request New Quote";
        var roja45quotationspro_cart_modified = 0;
        var roja45quotationspro_cartbutton_text = "Add To Quote";
        var roja45quotationspro_catalog_mode = 0;
        var roja45quotationspro_change_qty = 0;
        var roja45quotationspro_controller = "https:\/\/connexstore.co.za\/module\/roja45quotationspro\/QuotationsProFront?token=3a6692178813dd428366c5889a7a5c84";
        var roja45quotationspro_delete_products = 0;
        var roja45quotationspro_deleted_failed = "Unable to remove product from your request.";
        var roja45quotationspro_deleted_success = "Product removed from your request successfully.";
        var roja45quotationspro_enable_captcha = 0;
        var roja45quotationspro_enable_captchatype = 0;
        var roja45quotationspro_enable_inquotenotify = 1;
        var roja45quotationspro_enable_quote_dropdown = 0;
        var roja45quotationspro_enablequotecart = 1;
        var roja45quotationspro_enablequotecartpopup = 1;
        var roja45quotationspro_error_title = "Error";
        var roja45quotationspro_in_cart = 0;
        var roja45quotationspro_instantresponse = 0;
        var roja45quotationspro_label_position = "";
        var roja45quotationspro_new_quote_available = "A new quotation is available in your account.";
        var roja45quotationspro_productlistitemselector = "article.product-miniature";
        var roja45quotationspro_productlistselector_addtocart = "";
        var roja45quotationspro_productlistselector_buttons = "";
        var roja45quotationspro_productlistselector_flag = ".product-flags";
        var roja45quotationspro_productlistselector_price = ".product-price-and-shipping";
        var roja45quotationspro_productselector_addtocart = ".product-add-to-cart";
        var roja45quotationspro_productselector_price = "div.product-prices";
        var roja45quotationspro_productselector_qty = ".quote_quantity_wanted";
        var roja45quotationspro_quote_link_text = "Get A Quote";
        var roja45quotationspro_quote_modified = "Your cart has changed, you can request a new quote or reload an existing quote by clicking the link below.";
        var roja45quotationspro_recaptcha_site_key = "";
        var roja45quotationspro_request_buttons = "";
        var roja45quotationspro_responsivecartnavselector = "._desktop_quotecart";
        var roja45quotationspro_responsivecartselector = "#header .header-nav div.hidden-md-up";
        var roja45quotationspro_sent_failed = "Unable to send request. Please try again later.";
        var roja45quotationspro_sent_success = "Request received, we will be in touch shortly. Thank You.";
        var roja45quotationspro_show_label = 1;
        var roja45quotationspro_success_title = "Success";
        var roja45quotationspro_touchspin = 1;
        var roja45quotationspro_unknown_error = "An unexpected error has occurred, please raise this with your support provider.";
        var roja45quotationspro_usejs = 1;
        var roja45quotationspro_warning_title = "Warning";
        var wishlistAddProductToCartUrl = "https:\/\/connexstore.co.za\/module\/blockwishlist\/action?action=addProductToCart";
        var wishlistUrl = "https:\/\/connexstore.co.za\/module\/blockwishlist\/view";
    </script>
</head>
<body id="index" class="lang-en country-za currency-zar layout-left-column page-index tax-display-enabled live_edit_0  1.7.8.8 ps_178
	no_bg #ffffff bg_attatchment_fixed bg_position_tl bg_repeat_xy bg_size_initial slider_position_column slider_controls_black banners_top2 banners_top_tablets2 banners_top_phones1 banners_bottom2 banners_bottom_tablets2 banners_bottom_phones1 submenu1 pl_1col_qty_6 pl_2col_qty_6 pl_3col_qty_4 pl_1col_qty_bigtablets_4 pl_2col_qty_bigtablets_3 pl_3col_qty_bigtablets_2 pl_1col_qty_tablets_3 pl_1col_qty_phones_1 home_tabs2 pl_border_type2 45 14 12 pl_button_icon_no pl_button_qty2 pl_desc_no pl_reviews_no pl_availability_no  hide_reference_no hide_reassurance_yes product_tabs1    menu_sep0 header_sep1 slider_full_width feat_cat_style2 feat_cat6 feat_cat_bigtablets4 feat_cat_tablets4 feat_cat_phones0 all_products_yes pl_colors_yes newsletter_info_yes stickycart_yes stickymenu_yes homeicon_no pl_man_no product_hide_man_no  pl_ref_yes  mainfont_Poppins bg_white standard_carusele not_logged  ">
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=AW-749461096" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <main>
        <header id="header">
            <div class="header-banner">

            </div>
            <div class="header-top">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4 hidden-sm-down2" id="_desktop_logo">
                            <h1>
                                <a href="#Home"><img class="logo img-responsive" src="images/logo.jpg" alt="Connex Store"></a>
                            </h1>
                        </div>
                       
                        
                        <!-- Block search module TOP -->
                        <div id="_desktop_search_widget" class="col-lg-4 col-md-4 col-sm-12 search-widget hidden-sm-down ">
                            <div id="search_widget">
                                <form method="get" action="">
                                    <input type="hidden" name="controller" value="search">
                                    <input type="text" name="s" value="" placeholder="Search our catalog" aria-label="Search">
                                    <button type="submit">
                                        <i class="material-icons search">&#xE8B6;</i>
                                        <span class="hidden-xl-down">Search</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        
                        <!-- /Block search module TOP -->

                        <div id="_mobile_cart" class="rwd_menu_item"><div class="cart_top">

		<div class="blockcart cart-preview inactive" data-refresh-url="//connexstore.co.za/module/ps_shoppingcart/ajax">
			<div class="header">
			
            <div class="cart_index_title">
               
               <a class="cart_link" rel="nofollow" href="checkoutpage.php">
                   <i class="material-icons shopping-cart">shopping_cart</i>
                   <span class="hidden-sm-down cart_title">Cart:</span>
                   <span class="cart-products-count"><?php $total = 0;
foreach ($_SESSION as $key=>$val) {$total++;} echo $total;?>
                       <span> Products - R&nbsp;<?php echo("R$total"); ?></span>
                   </span>
               </a>
           </div>



				<div id="subcart">

					<ul class="cart_products">

											<li>There are no more items in your cart</li>
					
										</ul>

					<ul class="cart-subtotals">

				
						<li>
							<span class="text">Shipping</span>
							<span class="value"></span>
							<span class="clearfix"></span>
						</li>

						<li>
							<span class="text">Total</span>
							<span class="value">R&nbsp;</span>
							<span class="clearfix"></span>
						</li>

					</ul>

					<div class="cart-buttons">
						<a class="btn btn-primary viewcart" href="">Check Out <i class="material-icons"></i></a>
											</div>

				</div>

			</div>
		</div>

	</div></div>

    
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

<div class="footer-sub">
            <div class="container">
                <div class="row">
                    <div class="col-5">
                        <div class="label-sub">
                            Sign up for Superbalist
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="label-sub">
                            <div class="label-mov">
                                <form method="get" action="">
                                    <input type="text" name="s" value="" placeholder="Search our catalog" aria-label="Search">
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="label-sub">
                            <div id="search_widget">
                                <form method="get" action="">
                                    <button type="submit" value="Signup">Signup
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer id="footer">
            <div class="footer-container">
                <div class="container">
                    <div class="row">
                        <div class="h3 hidden-sm-down">SUPERBALIST</div>
                        <div class="col-md-2 links wrapper">
                            <div class="h4 hidden-sm-down"><span>Account</span></div>
                            <ul id="footer_sub_menu_89968" class="collapse">
                                <li>
                                    <a id="link-product-page-prices-drop-1" class="cms-page-link" href="#" title="View details into accounts">Account Details</a>
                                </li>
                                <li>
                                    <a id="link-product-page-new-products-1" class="cms-page-link" href="#" title="Our new products">
                                        Orders
                                    </a>
                                </li>
                                <li>
                                    <a id="link-product-page-best-sales-1" class="cms-page-link" href="#" title="Our best sales">
                                        Returns & Exchanges
                                    </a>
                                </li>
                                <li>
                                    <a id="link-product-page-best-sales-1" class="cms-page-link" href="#" title="Our best sales">
                                        Wishlist
                                    </a>
                                </li>
                                <li>
                                    <a id="link-product-page-best-sales-1" class="cms-page-link" href="#" title="Our best sales">
                                        Notifications Settings
                                    </a>
                                </li>
                                <li>
                                    <a id="link-product-page-best-sales-1" class="cms-page-link" href="#" title="Our best sales">
                                        Buy Gift Vouchers
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-2 links wrapper">
                            <div class="h4 hidden-sm-down"><span>Client Concierge</span></div>
                            <ul id="footer_sub_menu_18950" class="collapse">
                                <li>
                                    <a id="link-cms-page-1-2" class="cms-page-link" href="#" title="View out help Centre">
                                        Help Centre
                                    </a>
                                </li>
                                <li>
                                    <a id="link-cms-page-2-2" class="cms-page-link" href="#" title="Check out FAQs">
                                        FAQ
                                    </a>
                                </li>
                                <li>
                                    <a id="link-cms-page-3-2" class="cms-page-link" href="#" title="view out payment options">
                                        Payment Options
                                    </a>
                                </li>
                                <li>
                                    <a id="link-cms-page-4-2" class="cms-page-link" href="#" title="Learn about our delivery Options">
                                        Delivery Options
                                    </a>
                                </li>
                                <li>
                                    <a id="link-cms-page-5-2" class="cms-page-link" href="#" title="click and connect">
                                        Click & Connect
                                    </a>
                                </li>
                                <li>
                                    <a id="link-static-page-contact-2" class="cms-page-link" href="#" title="Check our return policy">
                                        Return Policy
                                    </a>
                                </li>
                                <li>
                                    <a id="link-static-page-sitemap-2" class="cms-page-link" href="#" title="View our Privacy policy">
                                        Privacy Policy
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-2 links wrapper">
                            <div class="h4 hidden-sm-down"><span>The Company</span></div>
                            <ul id="footer_sub_menu_18950" class="collapse">
                                <li>
                                    <a id="link-cms-page-1-2" class="cms-page-link" href="#" title="Read more about us">
                                        About Us
                                    </a>
                                </li>
                                <li>
                                    <a id="link-cms-page-2-2" class="cms-page-link" href="#" title="Check careers under Superbalist">
                                        Careers at Superbalist
                                    </a>
                                </li>
                                <li>
                                    <a id="link-cms-page-3-2" class="cms-page-link" href="#" title="view careers in Tech">
                                        Tech Careers
                                    </a>
                                </li>
                                <li>
                                    <a id="link-cms-page-4-2" class="cms-page-link" href="#" title="Learn more about our marketing services">
                                        Marketing Services
                                    </a>
                                </li>
                                <li>
                                    <a id="link-cms-page-5-2" class="cms-page-link" href="#" title="click and connect">
                                        Click & Connect
                                    </a>
                                </li>
                                <li>
                                    <a id="link-static-page-contact-2" class="cms-page-link" href="#" title="Check out out cop gift vouchers">
                                        Corporate Gift Vouchers
                                    </a>
                                </li>
                                <li>
                                    <a id="link-static-page-sitemap-2" class="cms-page-link" href="#" title="conscious Journey">
                                        Conscious Journey
                                    </a>
                                </li>
                                <li>
                                    <a id="link-static-page-sitemap-2" class="cms-page-link" href="#" title="view our conacts">
                                        Contact
                                    </a>
                                </li>
                                <li>
                                    <a id="link-static-page-sitemap-2" class="cms-page-link" href="#" title="View Human rights group under Takealot">
                                        Takealot Group Human Rights
                                    </a>
                                </li>
                                <li>
                                    <a id="link-static-page-sitemap-2" class="cms-page-link" href="#" title="Statements">
                                        Statement
                                    </a>
                                </li>
                                <li>
                                    <a id="link-static-page-sitemap-2" class="cms-page-link" href="#" title="speack up process">
                                        Speak Up Process
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-2 links wrapper">
                            <div class="h4 hidden-sm-down"><span>Takealot Group</span></div>
                            <ul id="footer_sub_menu_18950" class="collapse">
                                <li>
                                    <a id="link-cms-page-1-2" class="cms-page-link" href="#" title="View and Order on Mr D Foods">Mr D Foods</a>
                                </li>
                                <li>
                                    <a id="link-cms-page-2-2" class="cms-page-link" href="#" title="View and Order on Takealot">Takealot</a>
                                </li>

                            </ul>
                        </div>
                        <div class="col-md-3 links wrapper">
                            <div class="row">
                                <div class="h4 block-social hidden-sm-down"><span style="color:#3cd062">Log in to your Account</span></div>
                                <div class="block-social col-lg-8">
                                    <ul>
                                        <li class="fa fa-facebook"><a href="#" target="_blank"><span>Facebook</span></a></li>
                                        <li class="fa fa-twitter"><a href="#" target="_blank"><span>Twitter</span></a></li>
                                        <li class="fa fa-youtube"><a href="#" target="_blank"><span>YouTube</span></a></li>
                                        <li class="fa fa-instagram"><a href="#" target="_blank"><span>Instagram</span></a></li>
                                    </ul>
                                    <p id="block-social-label">Follow us</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bottom-footer">
                        <div class="links-foot">
                            <a href="#" title="">Terms & Conditions</a>
                            <a href="#" title="">Privacy Policy</a>
                            <a href="#" title="">Shopping Glossary</a>
                            <a href="#" title="">Fulfilment by Takealot Delivery Team</a>
                        </div>

                    </div>

                </div>

        </footer>
      
    </main>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    <script type="text/javascript" src="https://connexstore.co.za/themes/core.js"></script>
    <script type="text/javascript" src="https://connexstore.co.za/themes/AngarTheme/assets/js/theme.js"></script>
    <script type="text/javascript" src="https://connexstore.co.za/themes/AngarTheme/assets/js/libs/jquery.bxslider.min.js"></script>
    <script type="text/javascript" src="https://connexstore.co.za/themes/AngarTheme/assets/js/angartheme.js"></script>
    <script type="text/javascript" src="https://connexstore.co.za/modules/ps_emailsubscription/views/js/ps_emailsubscription.js"></script>
    <script type="text/javascript" src="https://connexstore.co.za/modules/blockreassurance/views/dist/front.js"></script>
    <script type="text/javascript" src="https://connexstore.co.za/modules/ps_emailalerts/js/mailalerts.js"></script>
    <script type="text/javascript" src="https://sfdr.co/sfdr.js?platform=prestashop"></script>
    <script type="text/javascript" src="https://connexstore.co.za/modules/productcomments/views/js/jquery.rating.pack.js"></script>
    <script type="text/javascript" src="https://connexstore.co.za/modules/productcomments/views/js/jquery.textareaCounter.plugin.js"></script>
    <script type="text/javascript" src="https://connexstore.co.za/modules/productcomments/views/js/productcomments.js"></script>
    <script type="text/javascript" src="https://connexstore.co.za/modules/angarfacebook/views/js/angarfacebook.js"></script>
    <script type="text/javascript" src="https://connexstore.co.za/modules/angarscrolltop/views/js/angarscrolltop.js"></script>
    <script type="text/javascript" src="https://connexstore.co.za/modules/prestanotifypro/views/js/shadowbox/shadowbox.js"></script>
    <script type="text/javascript" src="https://connexstore.co.za/modules/ets_banneranywhere/views/js/front.js"></script>
    <script type="text/javascript" src="https://connexstore.co.za/modules/roja45quotationspro/views/js/roja45quotationspro17.js"></script>
    <script type="text/javascript" src="https://connexstore.co.za/modules/roja45quotationspro/views/js/roja45quotationspro_cart17.js"></script>
    <script type="text/javascript" src="https://connexstore.co.za/modules/roja45quotationspro/views/js/validate.js"></script>
    <script type="text/javascript" src="https://connexstore.co.za/modules/roja45quotationspro/views/js/roja45quotationspro_preventcartmods17.js"></script>
    <script type="text/javascript" src="https://connexstore.co.za/modules/ps_googleanalytics/views/js/GoogleAnalyticActionLib.js"></script>
    <script type="text/javascript" src="https://connexstore.co.za/modules/ybc_productimagehover/views/js/productimagehover.js"></script>
    <script type="text/javascript" src="https://connexstore.co.za/modules/responsive/views/js/layout.js"></script>
    <script type="text/javascript" src="https://connexstore.co.za/modules/carrieronorder//views/js/front.js"></script>
    <script type="text/javascript" src="https://connexstore.co.za/modules/codwfeeplus/views/js/front.js"></script>
    <script type="text/javascript" src="https://connexstore.co.za/modules/codwfeeplus/views/js/front-reorder.js"></script>
    <script type="text/javascript" src="https://connexstore.co.za/js/jquery/ui/jquery-ui.min.js"></script>
    <script type="text/javascript" src="https://connexstore.co.za/modules/blockwishlist/public/product.bundle.js"></script>
    <script type="text/javascript" src="https://connexstore.co.za/js/jquery/plugins/fancybox/jquery.fancybox.js"></script>
    <script type="text/javascript" src="https://connexstore.co.za/js/jquery/plugins/growl/jquery.growl.js"></script>
    <script type="text/javascript" src="https://connexstore.co.za/themes/AngarTheme/modules/ps_searchbar/ps_searchbar.js"></script>
    <script type="text/javascript" src="https://connexstore.co.za/themes/AngarTheme/modules/ps_shoppingcart/ps_shoppingcart.js"></script>
    <script type="text/javascript" src="https://connexstore.co.za/modules/blockwishlist/public/graphql.js"></script>
    <script type="text/javascript" src="https://connexstore.co.za/modules/blockwishlist/public/vendors.js"></script>
    <script type="text/javascript" src="https://connexstore.co.za/themes/AngarTheme/assets/js/custom.js"></script>





    <script>
        $(window).load(function() {
            $('#angarslider').bxSlider({
                maxSlides: 1,
                slideWidth: 1920,
                infiniteLoop: true,
                auto: true,
                pager: 1,
                autoHover: 1,
                speed: 500,
                pause: 5000,
                adaptiveHeight: true,
                touchEnabled: true
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#bxslider_1').bxSlider({
                auto: true,
                minSlides: 5,
                maxSlides: 5,
                mode: 'vertical',
                pager: false,
                pause: 3000,
                nextSelector: '#home-next_1',
                prevSelector: '#home-prev_1',
                nextText: '>',
                prevText: '<',
                moveSlides: 5,
                infiniteLoop: true,
                hideControlOnEnd: true,
                useCSS: false,
            });
        });
    </script>
    <script type="text/javascript">
        var time_start;
        $(window).load(
            function() {
                time_start = new Date();
            }
        );
        $(window).unload(
            function() {
                var time_end = new Date();
                var pagetime = new Object;
                pagetime.type = "pagetime";
                pagetime.id_connections = "34732";
                pagetime.id_page = "12";
                pagetime.time_start = "2023-01-19 11:05:53";
                pagetime.token = "83103390224fc93e969bf51dca8edb97cf6553f0";
                pagetime.time = time_end - time_start;
                $.post("https://connexstore.co.za/index.php?controller=statistics", pagetime);
            }
        );
    </script>
<script>
    function validate(){
        var cpassword = document.getElementById('cpassword').value;
        var cpassword2 = document.getElementById('cpassword2').value;
        if( cpassword !==  cpassword2){
            alert("Passwords do not match");
            return false;
        } else{
            return true;
        }
    }
    </script>
    

</body>

</html>
</div>
</body>
</html>