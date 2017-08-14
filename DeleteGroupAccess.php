<?php session_start();  ?>
<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
}

include('config.php');
$chat_header_id = $_SESSION['chat_header_id'];
$chat_creator = $_SESSION['user_id'];
$group_id = $_GET['group_id'];
try
{
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username,$password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmnt = $conn->prepare("DELETE FROM tbl_access WHERE (chat_header_id=:chat_header_id AND group_id=:group_id) AND user_id!=:chat_creator"); 

$stmnt->bindParam(':chat_header_id',$chat_header_id);
$stmnt->bindParam(':group_id',$group_id);
$stmnt->bindParam(':chat_creator',$chat_creator);

$stmnt->execute();
$conn=NULL;

}

catch(PDOException $e)
{
echo "Error: " . $e->getMessage();
}

echo "<script>window.location.href='UpdateMessage.php?chat_header_id=$chat_header_id'</script>";
?>