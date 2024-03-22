<?php 

include("../db_connect.php");

$id = file_get_contents("php://input");

$sql = "DELETE FROM products WHERE Id = $id";

$conn->query($sql);

echo json_encode(["status" => 200, "result" => "Data Delete Successfully"]);

?>