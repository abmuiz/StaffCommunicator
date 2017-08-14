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

    function select_tbl_chat_header($chat_header_id)
    {
        include('config.php');
        try
        {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username,$password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmnt = $conn->prepare("SELECT `chat_header_id` FROM tbl_chat_header WHERE chat_header_id=:chat_header_id"); 

        $stmnt->bindParam(':chat_header_id',$chat_header_id);

        $stmnt->execute();
        $records = $stmnt->fetchAll();
        $conn=null;
        return $records;
        }

        catch(PDOException $e)
        {
        echo "Error: " . $e->getMessage();
        }
    }

    function select_tbl_user($user_id)
    {
        include('config.php');

        try
        {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username,$password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmnt = $conn->prepare("SELECT `name` FROM tbl_user WHERE user_id=:user_id"); 

        $stmnt->bindParam(':user_id',$user_id);

        $stmnt->execute();
        $records = $stmnt->fetchAll();
        foreach ($records as $value) {
            # code...
            $name = $value['name'];
        }
        $conn=null;
        return $name;
        }

        catch(PDOException $e)
        {
        echo "Error: " . $e->getMessage();
        }

    }

    function get_message_type($message_id)
    {
        switch ($message_id) {
            case 1:
                return 'Parent Circular';
                break;
            
            case 2:
                return 'Group Message';
                break;
            case 3:
                return 'Individual Message';
                    break;
            default:
                return 'Message';
                break;
        }
    }
    
    $user_id=$_SESSION['user_id'];
    $chat_header_id = $_GET['chat_header_id'];
    if (empty(select_tbl_chat_header($chat_header_id))) {
        echo "<script>alert('The Message is not available');</script>";
        echo "<script>window.location.href = 'Messages.php';</script>";
    }
    include('config.php');  
    
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    if(isset($_POST['submit']))
    {

        
        $stmnt = $conn->prepare("INSERT INTO tbl_chat (chat_header_id,chat,user_id,user_name,chat_date)values(:chat_header_id,:chat,:user_id,:user_name,:chat_date)");
    //description
        $stmnt->bindParam(':chat_header_id',$chat_header_id);
        $stmnt->bindParam(':chat',$chat);
        $stmnt->bindParam(':user_id',$user_id);
        $stmnt->bindParam(':user_name',$user_name);
        $stmnt->bindParam(':chat_date',$chat_date);

        $chat_header_id=(int)$_POST['chat_header_id'];
        $chat=htmlspecialchars($_POST['chat']);
        $user_id=$_SESSION['user_id'];
        $user_name = $_SESSION['name'];
        $chat_date = date("Y-m-d H:i:s");
        
        $stmnt->execute();

    }


    $stmnt = $conn->prepare("SELECT * FROM tbl_chat_header where chat_header_id=:chat_header_id");
    //$stmnt = $conn->prepare("SELECT chat_header_id,access_right FROM tbl_access where user_id=:user_id");
    $stmnt->bindParam(':chat_header_id',$chat_header_id);
    $stmnt->execute();
    $records = $stmnt->fetchAll();



    foreach ($records as $record) {
        # code...
        $chat_title = $record['chat_title'];
        $chat_description = $record['chat_description'];
        $chat_creator = $record['chat_creator'];
        $message_id = $record['message_type'];

    }

    $stmnt = $conn->prepare("SELECT access_right,chat_read,override_flag FROM tbl_access where chat_header_id=:chat_header_id AND user_id=:user_id");
    //$stmnt = $conn->prepare("SELECT chat_header_id,access_right FROM tbl_access where user_id=:user_id");
    $stmnt->bindParam(':chat_header_id',$chat_header_id);
    $stmnt->bindParam(':user_id',$user_id);
    $stmnt->execute();
    $records = $stmnt->fetchAll();

    $access_right_array=array();
    $access_right=0;
    foreach ($records as $record) {
        # code...
        $access_right_array[] = (int)$record['access_right'];
        $chat_read = (int)$record['chat_read'];
        $override_flag = (int)$record['override_flag'];
        
    }
    
    if($override_flag==1)
        echo "<script>window.location.href = 'Messages.php';</script>";
    
    if(in_array(1, $access_right_array) || $override_flag==3)
    $access_right=1;

    if($override_flag==2)
        $access_right=0;

    if($chat_read==0)
    {
        $stmnt = $conn->prepare("UPDATE tbl_access SET chat_read=1 WHERE user_id=:user_id AND chat_header_id=:chat_header_id");
        $stmnt->bindParam(':chat_header_id',$chat_header_id);
        $stmnt->bindParam(':user_id',$user_id);
        $stmnt->execute();
    }

    $stmnt = $conn->prepare("SELECT * FROM tbl_chat where chat_header_id=:chat_header_id ORDER BY chat_date DESC");
    
    $stmnt->bindParam(':chat_header_id',$chat_header_id);
    $stmnt->execute();
    $records = $stmnt->fetchAll();
    $conn = null;
    
?>

<html lang="en">


<head>
<?php include 'Head.php'; ?>
<style type="text/css">

</style>
<script src='https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=h8wn6a4bfxuursuf2edoenxwnirt0ijijz8ty6gi1jh5m370'></script>
  <script>
  tinymce.init({
    selector: '#chat',
    menubar:false   
  });
  </script>
</head>


<body >
<?php include 'MessageHeader.php'; ?>


<div class="container">
    <div class="row">
     <div class="col-sm-5">
      <div  data-spy="affix" data-offset-top="200">
       
        <h2 ><?php echo $chat_title; ?></h2><p><?php echo "[".get_message_type($message_id)."]"; ?></p>
        <hr>
        <p ><b>Group Creator: </b><?php echo select_tbl_user($chat_creator); ?></p>
        <p ><b>Description: </b><?php echo $chat_description; ?></p>
    
    

        <input type="hidden" name="chat_header_id" class="form-control" id="chat_header_id" value="<?php echo $chat_header_id; ?>">
        
        <?php if($access_right==1)
            {

            echo '
                <div class="form-group">
                    <label for="chat" >Enter Message:</label>
                    <textarea name="chat" class="form-control" id="chat">
                    </textarea>
                </div>
                
                <button name="submit" id="button" class="btn btn-primary">Post </button>
             ';
            }
        ?>
       
      </div>
     </div>
            
        <div class="col-sm-7">
        <p style="margin-top: 25px" >Total Messages: <span id="total_chats"><?php echo select_tbl_chat($chat_header_id); ?></span></p>
            <div id="screen" style="margin-top: 25px">
            
                <?php
                    
                    foreach ($records as $value) {
                        # code...
                        $chat = $value['chat'];
                        $chat_id = $value['chat_id'];
                        $name = $value['user_name'];
                        $chat_date = $value['chat_date'];
                        $check_date = date('Y-m-d', strtotime( $chat_date));
                        
                        if($check_date==date("Y-m-d"))
                            $chat_date = date('H:i:s', strtotime( $chat_date ));
                        else
                            $chat_date = date('d/m/Y', strtotime( $chat_date ));  
                        echo"

                            <div class='talk-bubble tri-right left-top' >
                              <div class='talktext' id=$chat_id>
                                <span class='bla'>$name &nbsp $chat_date</span>
                                <p class='h' >$chat</p>
                              </div>
                            </div>
                        ";
                       

                    }

                    

                ?>
            </div>
        </div>
            
        	
      	</div>
    </div>



<script type="text/javascript" src="ChatOperation.js">   
</script>
</body>
</html>