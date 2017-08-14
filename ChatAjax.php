<?php session_start(); ?>
<?php
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php");
    }

    function select_tbl_chat($chat_header_id)
    {
        include('config.php');

        try
        {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username,$password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmnt = $conn->prepare("SELECT count(chat_id) FROM tbl_chat WHERE chat_header_id=:chat_header_id");

        $stmnt->bindParam(':chat_header_id',$chat_header_id);

        $stmnt->execute();
        $records = $stmnt->fetchAll();
        $conn=null;
        
        foreach ($records as $value) {
            # code...
            $count=$value['count(chat_id)'];
        }
        return $count;
        }

        catch(PDOException $e)
        {
        echo "Error: " . $e->getMessage();
        }

    }

    $user_id = $_SESSION['user_id'];
    $name = $_SESSION['name'];

    include('config.php');  
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    
    $chat_header_id = $_POST['chat_header_id'];
    $last_row = $_POST['last_row'];

    if (!empty($_POST['chat']) && $_POST['chat']!="") {
    	# code...
    $chat = $_POST['chat'];
    $stmnt = $conn->prepare("INSERT INTO tbl_chat(chat_header_id,chat,user_id,user_name,chat_date) VALUES(:chat_header_id,:chat,:user_id,:user_name,:chat_date)");
    
    $stmnt->bindParam(':chat_header_id',$chat_header_id);
    $stmnt->bindParam(':chat',$chat);
    $stmnt->bindParam(':user_id',$user_id);
    $stmnt->bindParam(':user_name',$name);
    $stmnt->bindParam(':chat_date',$chat_date);
    $chat_date = date('Y-m-d H:i:s');
    $stmnt->execute();
    
    }

    if ($last_row==0) {
        # code...
        $stmnt = $conn->prepare("SELECT * from tbl_chat where chat_header_id=:chat_header_id ORDER BY chat_date DESC");
    }
    else
    {
    $stmnt = $conn->prepare("SELECT * from tbl_chat where chat_header_id=:chat_header_id AND chat_id>:last_row ORDER BY chat_date DESC");
    $stmnt->bindParam(':last_row',$last_row);
    }
    
    $stmnt->bindParam(':chat_header_id',$chat_header_id);
    
    $stmnt->execute();
    $records = $stmnt->fetchAll();

    $myJSON="";
    $list = array();
    $total_chats = select_tbl_chat($chat_header_id);
    foreach ($records as $value) {
    	# code...
        $chat_id = $value['chat_id']; 
    	$chat = $value['chat'];
    	$name = $value['user_name'];
    	$chat_date = $value['chat_date'];
        $chat_date = date_create($chat_date);
        $chat_date = date_format($chat_date,"d/m/Y");
    	
        $myObj = new \stdClass();

        $myObj->chat_id = $chat_id;
        $myObj->chat = $chat;
        $myObj->name = $name;
        $myObj->chat_date = $chat_date;
        $myObj->total_chats = $total_chats;        
                    
        $list[] = $myObj;
        $myObj=NULL;

    }

    

    /*$countObj = new \stdClass();
    $countObj->count = select_tbl_chat($chat_header_id);
    $list = $countObj;*/
    $myJSON = json_encode($list);
    echo $myJSON;
 
?>