<?php session_start(); ?>

<?php
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php");
    }
    $user_id=$_SESSION['user_id'];

    include('config.php');  
    
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmnt = $conn->prepare("SELECT * FROM tbl_chat_header WHERE chat_creator=:user_id ORDER BY creation_date DESC");
    
    $stmnt->bindParam(':user_id',$user_id);
    $stmnt->execute();
    $records = $stmnt->fetchAll();
    
?>

<html lang="en">
<head>
<?php include 'Head.php'; ?>
</head>
<body class="main-body">
<?php include 'MessageHeader.php'; ?>
<div class="container">

    <h2 >My Messages</h2>
    
    <div class="pull-right form-group">
    <button type="button" class="btn btn-primary" onclick="window.location.href='CreateMessage.php'">Create New Message</button>
    </div>
    <div class="row">
        <div class="col-sm-12">
        	
                    <?php

                    if(!empty($records))
                    {
                        echo '
                        <table class="table table-striped table-bordered">
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Type of Message</th>                        
                        <th>Date of Message</th>
                        <th>View</th>
                        <th>Modify</th>
                        <th>Delete</th>             
                    </tr>';
                    foreach ($records as $record) {
                        # code...
                        $chat_header_id = $record['chat_header_id'];
                        $chat_title = $record['chat_title'];                        
                        $chat_description = $record['chat_description'];
                        $chat_creator = $record['chat_creator'];
                        $creation_date = $record['creation_date'];
                        $creation_date = date_create($creation_date);
                        $creation_date = date_format($creation_date,"d/m/Y");
                        $message_type = (int)$record['message_type'];
                        if($message_type==1)
                        {
                            
                            $type_name = 'Parent Circular';
                        }

                        elseif($message_type==2)
                        {
                            
                            $type_name = 'Group Message';
                        }


                        elseif($message_type==3)
                        {
                            
                            $type_name = 'Individual Message';
                        }


                            echo "
                                <tr>
                                    <td class='col-sm-2'>$chat_title</td>
                                    <td class='col-sm-4' ><p class='description2'>$chat_description</p></td>
                                    <td class='col-sm-2'>$type_name</td>                                                    
                                    <td class='col-sm-1'>$creation_date</td>
                                    <td ><a href='Chat.php?chat_header_id=$chat_header_id'>View</a></td>
                                    <td><a href='UpdateMessage.php?chat_header_id=$chat_header_id'>Modify</a></td>
                                    <td><a href='DeleteMessage.php?chat_header_id=$chat_header_id'>Delete</a></td>
                                    
                                </tr>";                
                                            
                    }
                }
                    ?>
                </table>
      	</div>
    </div>

</body>
</html>
<?
$conn = null;
?>