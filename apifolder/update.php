<?php

include("../db_connect.php");

$json_data = file_get_contents("php://input");

$data_obj = json_decode($json_data);

// print_r($data_obj);

$id = $data_obj->up_id;
$name = $data_obj->up_name;
$qty = $data_obj->up_qty;
$price = $data_obj->up_price;
$category = $data_obj->up_category;

if (empty($name)) {
    $errors[] = "Please Enter Product Name";
}

if (empty($qty)) {
    $errors[] = "Please Enter Product Quantity";
}

if (empty($price)) {
    $errors[] = "Please Enter Product Price";
}

if (empty($category)) {
    $errors[] = "Please Enter Product Category";
}

if (!empty($errors)) {
    echo json_encode(["status" => 400, "result" => $errors]);
} else {
    $sql = "UPDATE `products` SET `Name`='$name',`Quantity`='$qty',`Price`='$price',`Category`='$category' WHERE Id = $id";
    
    if ($conn->query($sql)) {
        echo json_encode(["status" => 200, "result" => "Product Update Successfully"]);
    } else {
        echo json_encode(["status" => 400, "result" => "There was an error In Updating Data"]);
    }
    
}


?>