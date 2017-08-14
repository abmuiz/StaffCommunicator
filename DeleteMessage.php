<?php session_start(); ?>
<?php
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php");
    }
    
    include('config.php');  
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    $stmnt = $conn->prepare("DELETE FROM tbl_chat_header WHERE chat_header_id=:chat_header_id");
    $stmnt->bindParam(':chat_header_id',$chat_header_id);
    $chat_header_id=$_GET['chat_header_id'];
    $stmnt->execute();   

    echo '<script>alert("Message Deleted Successfully")</script>';
    echo "<script type='text/javascript'>window.location.href ='ListMessages.php'</script>";

?>