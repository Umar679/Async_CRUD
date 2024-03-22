<?php

include("../db_connect.php");

$json_data = file_get_contents("php://input");

$data_obj = json_decode($json_data);

$errors = []; // Initialize an array to store errors

$name = $data_obj->name;
$qty = $data_obj->qty;
$price = $data_obj->price;
$category = $data_obj->category;

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
    // If there are errors, encode and echo the array of errors
    echo json_encode(["status" => 400, "result" => $errors]);
} else {
    // If no errors, proceed with database insertion
    $conn->query("INSERT INTO `products`(`Name`, `Quantity`, `Price`, `Category`) VALUES ('$name','$qty','$price','$category')");

    echo json_encode(["status" => 200, "result" => "Saved Data Successfully"]);
}

?>