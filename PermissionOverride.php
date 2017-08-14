<?php session_start();  ?>
<?php
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php");
    }

    include('config.php'); 
    include 'BasicFunctions.php';
    
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $chat_creator = $_SESSION['user_id'];

    if (isset($_GET['action'])&&isset($_GET['permission_override'])) {
        # code...
        $action = (int)$_GET['action'];
        $permission_override = $_GET['permission_override'];
        $chat_header_id = $_SESSION['chat_header_id'];
        $user_array =array();
        $user_array = explode(",", $permission_override);

        
        if($action==0)
        {
            foreach ($user_array as $key => $value) {
                # code...
                update_tbl_access(0,$chat_header_id,$value);
            }
            
        }

        elseif($action==1)
        {
            foreach ($user_array as $key => $value) {
                # code...
                update_tbl_access(1,$chat_header_id,$value);
            }
            
        }

        elseif($action==2)
        {
            foreach ($user_array as $key => $value) {
                # code...
                update_tbl_access(2,$chat_header_id,$value);
            }
            
        }

        elseif($action==3)
        {
            foreach ($user_array as $key => $value) {
                # code...
                update_tbl_access(3,$chat_header_id,$value);
            }
            
        }

    }
echo "<script>window.location.href='UpdateMessage.php?chat_header_id=$chat_header_id'</script>";

?>