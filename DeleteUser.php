<?php session_start(); ?>
<?php
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php");
    }
    
    include('config.php');  
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    $stmnt = $conn->prepare("DELETE FROM tbl_user WHERE user_id=:modify_user_id");
    $stmnt->bindParam(':modify_user_id',$modify_user_id);
    $modify_user_id=$_GET['modify_user_id'];
    $stmnt->execute();   

    echo '<script>alert("User Deleted Successfully")</script>';
    echo "<script type='text/javascript'>window.location.href ='ListUsers.php'</script>";

?>