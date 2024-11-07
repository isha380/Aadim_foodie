<?Php
session_start();
include "../../pages/database/connection.php";

if(isset($_GET['id'])){
    $id=$_GET['id'];
    $query= "DELETE FROM student_info Where Id=?";
    $stmt=$conn->prepare($query);
    $stmt->bind_param("i",$id);

    if($stmt-> execute()){
        header("Location: user_content.php");
        exit();
    }else{
        echo "Erro deleting record:".$conn->error;
    }
    $stmt->close();
}else{
    echo "Invlaid request.";
}
$conn->close();


?>