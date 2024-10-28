<?php 
$server ="localhost";
$user ="root";
$pw ="";
$db="aadim_foodie";
$conn = new mysqli($server,$user,$pw,$db);
if($conn-> connect_error){
    die("connection FAILED !".$conn->connect_error);
}
// echo "connection SUCCESSFUL";
// $conn->close();
?>