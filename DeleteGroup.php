<?php session_start(); ?>
<?php
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php");
    }
    include('config.php');  
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    $stmnt = $conn->prepare("DELETE FROM tbl_group WHERE group_id=:group_id");
    $stmnt->bindParam(':group_id',$group_id);
    $group_id=$_GET['group_id'];
    $stmnt->execute();   

    echo '<script>alert("Group Deleted Successfully")</script>';
    echo "<script type='text/javascript'>window.location.href ='ListGroups.php'</script>";

?>