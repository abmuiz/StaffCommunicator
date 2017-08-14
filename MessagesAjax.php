<?php session_start(); ?>
<?php
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php");
    }

  $user_id=(int)$_POST['user_id'];
  $last_row = (int)$_POST['last_row'];

    include('config.php');  
    
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmnt = $conn->prepare("SELECT DISTINCT H.chat_header_id,chat_title,chat_description,chat_creator,creation_date,message_type,chat_read FROM tbl_chat_header H INNER JOIN tbl_access A ON H.chat_header_id=A.chat_header_id where user_id=:user_id AND H.chat_header_id>:last_row AND override_flag!=1 ORDER BY creation_date DESC ");
    
    $stmnt->bindParam(':user_id',$user_id);
    $stmnt->bindParam(':last_row',$last_row);

    $stmnt->execute();
    $records = $stmnt->fetchAll();

    $myJSON="";
    $list = array();
    
    foreach ($records as $record) {
                        # code...
        $chat_header_id = $record['chat_header_id'];
        $chat_title = $record['chat_title'];                        
        $chat_description = $record['chat_description'];
        $chat_creator = $record['chat_creator'];
        $creation_date = $record['creation_date'];
        $check_date = date('Y-m-d', strtotime( $creation_date ));
                        
        if($check_date==date("Y-m-d"))
            $creation_date = date('H:i:s', strtotime( $creation_date ));
        else
            $creation_date = date('d/m/Y', strtotime( $creation_date ));

        $message_type = (int)$record['message_type'];
        $chat_read = (int)$record['chat_read'];

            $stmnt = $conn->prepare("SELECT name from tbl_user WHERE user_id=:chat_creator");
            $stmnt->bindParam(':chat_creator',$chat_creator);
            $stmnt->execute();
            $user_data = $stmnt->fetchAll();  

            foreach ($user_data as $value) {
            $name = $value['name'];
            }     

            $myObj = new \stdClass();

            $myObj->chat_header_id = $chat_header_id;
            $myObj->chat_title = $chat_title;
            $myObj->chat_description = $chat_description;
            $myObj->chat_creator = $name;
            $myObj->creation_date = $creation_date;
            $myObj->message_type = $message_type;
            $myObj->chat_read = $chat_read;            
                        
            $list[] = $myObj;
            $myObj=NULL;
                                                                                              
    }
                   
        $myJSON = json_encode($list);
        echo $myJSON;               

?>