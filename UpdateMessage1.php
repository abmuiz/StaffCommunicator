<?php session_start();  ?>

<?php
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php");
    }
    include('config.php');  
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $chat_creator = $_SESSION['user_id'];

    if (isset($_POST['submit'])) 
    {
        # code...
        $stmnt = $conn->prepare("UPDATE tbl_chat_header SET chat_title=:chat_title,chat_description=:chat_description,message_type=:message_type where chat_header_id=:chat_header_id");

    
        $stmnt->bindParam(':chat_title',$chat_title);
        $stmnt->bindParam(':chat_description',$chat_description);
        $stmnt->bindParam(':message_type',$message_type);
        $stmnt->bindParam(':chat_header_id',$chat_header_id);
        

        $chat_title = $_POST['chat_title'];
        $chat_description = $_POST['chat_description'];
        $message_type = (int)$_POST['message_type'];
        $chat_header_id = $_POST['chat_header_id'];
        
        $stmnt->execute();

        $stmnt = $conn->prepare("DELETE FROM tbl_access WHERE chat_header_id=:chat_header_id");
        $stmnt->bindParam(':chat_header_id',$chat_header_id);        
        $stmnt->execute();

        $stmnt = $conn->prepare("INSERT INTO tbl_access (chat_header_id,access_right,user_id)values(:chat_header_id,:access_right,:user_id)");
            //description
        $stmnt->bindParam(':chat_header_id',$chat_header_id);
        $stmnt->bindParam(':access_right',$access_right);
        $stmnt->bindParam(':user_id',$chat_creator);
        $access_right=1;
        $stmnt->execute();

        if (!empty($_POST['perm_write'])) 
    {
        $perm_write = $_POST['perm_write'];
        foreach ($perm_write as $id) {
        # code...
            if($id!=$chat_creator)
            {
            $stmnt = $conn->prepare("INSERT INTO tbl_access (chat_header_id,access_right,user_id)values(:chat_header_id,:access_right,:user_id)");
    //description
                $stmnt->bindParam(':chat_header_id',$chat_header_id);
                $stmnt->bindParam(':access_right',$access_right);
                $stmnt->bindParam(':user_id',$id);
                //$stmnt->bindParam(':creation_date',$creation_date);
                $access_right=1;

                $stmnt->execute();
            }
    }

    }

    if (!empty($_POST['perm_read'])) 
    {
        $perm_read = $_POST['perm_read'];
    foreach ($perm_read as $id) {         

        if (!in_array($id, $perm_write) && $id!=$chat_creator) {
            # code...
            $stmnt = $conn->prepare("INSERT INTO tbl_access (chat_header_id,access_right,user_id)values(:chat_header_id,:access_right,:user_id)");
    //description
                $stmnt->bindParam(':chat_header_id',$chat_header_id);
                $stmnt->bindParam(':access_right',$access_right);
                $stmnt->bindParam(':user_id',$id);
                //$stmnt->bindParam(':creation_date',$creation_date);
                $access_right=0;

                $stmnt->execute();
        }
        

    }
    } 

        echo '<script>alert("User Details Updated Successfully")</script>';
        echo "<script type='text/javascript'>window.location.href ='ListMessages.php'</script>";
    }

        $stmnt = $conn->prepare("SELECT * FROM tbl_chat_header WHERE chat_header_id=:chat_header_id");
        $stmnt->bindParam(':chat_header_id',$chat_header_id); 
        $chat_header_id = $_GET['chat_header_id'];
        $stmnt->execute();
        $records = $stmnt->fetchAll();
        foreach ($records as $record) 
        {
                        # code...
                        $chat_title=$record['chat_title'];
                        $chat_description=$record['chat_description'];
                        $message_type=$record['message_type'];
                        
        }

        $stmnt = $conn->prepare("SELECT * FROM tbl_access WHERE chat_header_id=:chat_header_id");
        $stmnt->bindParam(':chat_header_id',$chat_header_id); 
        
        $stmnt->execute();
        $records = $stmnt->fetchAll();

        $user_list = array();
        $permission = array();

        $i=0;
        foreach ($records as $record) {
            # code...
            $user_list[$i] = $record['user_id'];
            $permission[$i] = $record['access_right'];
            $i++;
        }


        $stmnt = $conn->prepare("SELECT * FROM tbl_user");    
        $stmnt->execute();
        $records = $stmnt->fetchAll();


  
?>


<html lang="en">
<?php include 'Head.php'; ?>
</head>
<body class="main-body">
<?php include 'MessageHeader.php'; ?>
<div class="container">
    <h2 class="lead">Modify User Details</h2>
    <div class="row">
        <div class="col-sm-6">

    <form id="myform" method="POST" action="" data-toggle="validator" role="form">
        
                    <div class="form-group">
                        <label for="title">Modify Title:</label>
                        <input type="text" name="chat_title" class="form-control" id="pressnotename" value="<?php echo $chat_title;?>" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Modify Description:</label>
                        <input type="text" name="chat_description"  class="form-control" id="description" value="<?php echo $chat_description;?>">
                    </div>
                    <div class="form-group" >
                            <label>Select Type of Message</label>
                            <select name="message_type" class="form-control" >
                                <option value="1">Parent Circular</option>
                                <option value="2">Group Messages</option>
                                <option value="3">Individual Messages</option>
                            </select>
                    </div>
                    <label for="description">Select Users for Message:</label>
                    
                    
                    <span style="margin-right: 52px;margin-left: 80px"><input type="checkbox" id="checkAllREAD">Check All</span>

                    <span >
                     <input type="checkbox" id="checkAllWRITE">Check All   
                    </span>

                    <table class="table table-striped table-bordered" id="mytable">
                    <tr>
                        <th>User Name</th>
                        <th>Read</th>
                        <th>Write</th>             
                    </tr>
                    
                    <?php

                    foreach ($records as $record) {
                        # code...
                        $name = $record['name'];
                        $each_user_id = $record['user_id'];
                        //$chat_title = $record['chat_title'];                        
                            //$chat_description = $record['chat_description'];
                        if (in_array($each_user_id, $user_list)) {
                            # code...
                            $key = (int)array_search($each_user_id, $user_list);

                            if ($permission[$key] == 0) {
                                # code...
                                echo "<tr>
                                    <td>$name</td>

                                    <td><input type='checkbox' name='perm_read[]' value='$each_user_id' checked></td>                  
                                    <td><input type='checkbox' name='perm_write[]' value='$each_user_id'></td>                 
                                </tr>";
                            }

                            else
                            {
                                echo "<tr>
                                    <td>$name</td>

                                    <td><input type='checkbox' name='perm_read[]' value='$each_user_id'></td>                  
                                    <td><input type='checkbox' name='perm_write[]' value='$each_user_id' checked></td>                 
                                </tr>";
                            }
                        }
                        else
                        {
                            echo "
                                <tr>
                                    <td>$name</td>

                                    <td><input type='checkbox' name='perm_read[]' value='$each_user_id' ></td>                  
                                    <td><input type='checkbox' name='perm_write[]' value='$each_user_id'></td>                 
                                </tr>";

                            
                        }
                        
                    
                }
                    ?>
                </table>
              
              <input type="hidden" name="chat_header_id" value="<?php echo $chat_header_id; ?>">
              <div class="form-group">
                    <input type="submit" name="submit" value="Update" class="btn btn-primary">            
              </div>
            
    </form>
    </div>
    </div>
</div>  
<script type="text/javascript">
    $("#checkAllREAD").click(function () {
     $("#mytable tr td:nth-child(2) input[type=checkbox]").not(this).prop('checked', this.checked);

 });

    $("#checkAllWRITE").click(function () {
     $("#mytable tr td:nth-child(3) input[type=checkbox]").not(this).prop('checked', this.checked);

 });

</script>
   
</body>
</html>