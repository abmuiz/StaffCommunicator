<?php session_start(); ?>
<?php
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php");
    }
    include('config.php');  
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $chat_creator = $_SESSION['user_id'];

    if(isset($_POST['submit']))
  {

    $chat_title = $_POST['chat_title'];
    $chat_description = $_POST['chat_description'];
    
    
    
    $each_user_id = $_POST['each_user_id'];
    $message_type = (int)$_POST['message_type'];




    $stmnt = $conn->prepare("INSERT INTO tbl_chat_header (chat_title,chat_description,chat_creator,creation_date,message_type)values(:chat_title,:chat_description,:chat_creator,:creation_date,:message_type)");
    //description
    $stmnt->bindParam(':chat_title',$chat_title);
    $stmnt->bindParam(':chat_description',$chat_description);
    $stmnt->bindParam(':chat_creator',$chat_creator);
    $stmnt->bindParam(':creation_date',$creation_date);
    $stmnt->bindParam(':message_type',$message_type);

    $creation_date = date('Y-m-d H:i:s');
    
    $stmnt->execute();

    $stmnt = $conn->prepare("SELECT chat_header_id FROM tbl_chat_header WHERE chat_creator=:chat_creator AND creation_date=:creation_date");
    $stmnt->bindParam(':chat_creator',$chat_creator);
    $stmnt->bindParam(':creation_date',$creation_date);
    $stmnt->execute();
    $records = $stmnt->fetchAll();

    foreach ($records as $record) {
      # code...
      $chat_header_id = $record['chat_header_id'];



    }

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
        foreach ($perm_write as $id) 
        {
            if($id!=$chat_creator)
            {
            $stmnt = $conn->prepare("INSERT INTO tbl_access (chat_header_id,access_right,user_id)values(:chat_header_id,:access_right,:user_id)");
            //description
            $stmnt->bindParam(':chat_header_id',$chat_header_id);
            $stmnt->bindParam(':access_right',$access_right);
            $stmnt->bindParam(':user_id',$id);
            $access_right=1;
            $stmnt->execute();
        	}
        }

    }

    if (!empty($_POST['perm_read'])) 
    {
        $perm_read = $_POST['perm_read'];
        foreach ($perm_read as $id) 
        {         

            if (!in_array($id, $perm_write) && $id!=$chat_creator) 
            {
                # code...
                $stmnt = $conn->prepare("INSERT INTO tbl_access (chat_header_id,access_right,user_id)values(:chat_header_id,:access_right,:user_id)");

                $stmnt->bindParam(':chat_header_id',$chat_header_id);
                $stmnt->bindParam(':access_right',$access_right);
                $stmnt->bindParam(':user_id',$id);
                $access_right=0;
                $stmnt->execute();
            }
            

        }
    }   


    $conn = null;
    echo '<script>alert("Message Added Successfully")</script>';
    echo "<script>window.location.href='ListMessages.php'</script>";
  }

    $stmnt = $conn->prepare("SELECT * FROM tbl_user");
    $stmnt->execute();
    $records = $stmnt->fetchAll();

    $stmnt = $conn->prepare("SELECT * FROM tbl_group WHERE group_creator=:chat_creator");
    $stmnt->bindParam(':chat_creator',$chat_creator);
    $stmnt->execute();
    $groups = $stmnt->fetchAll();
    
       
?>


<html lang="en">
<head>
<?php include 'Head.php'; ?>
</head>
<body class="main-body">
<?php include 'MessageHeader.php' ?>
<div class="container">
    <h2 class="lead">Post Message</h2>
            
    <div class="row">
        <div class="col-sm-6">
            <form id="myform" method="POST" action="CreateMessageNext.php" data-toggle="validator" role="form">
                
                <div class="form-group">
                    <label for="title">Enter Title:</label>
                    <input type="text" name="chat_title" class="form-control" id="pressnotename" required>
                </div>
                <div class="form-group">
                    <label for="description">Enter Description:</label>
                    <textarea name="chat_description" class="form-control" id="description"></textarea>
                </div>
                <div class="form-group" >
                        <label>Select Type of Message</label>
                        <select name="message_type" class="form-control" >
                            <option value="1">Parent Circular</option>
                            <option value="2">Group Messages</option>
                            <option value="3">Individual Messages</option>
                        </select>
                </div> 
                                       
                <div class="form-group">
                <label for="description">Select Group for Message:</label>
                <span style="margin-right: 60px;margin-left: 175px"><input type="checkbox" id="checkAllREAD">Check All</span>

                    
                <table class="table table-striped table-bordered" id="mytable">
                    <tr>
                        <th>Group Name</th>
                        <th>Check</th>             
                    </tr>
                    <tr>
                        <td>All Members</td>
                        <td><input type='checkbox' name='all_users' value='all_users'></td>                                         
                    </tr>
                    <?php

                    foreach ($groups as $group) {
                        # code...
                        $group_name = $group['group_name'];
                        $each_group_id = (int)$group['group_id'];
                        if($each_group_id!=0)
                        echo "
                            <tr>
                                    <td>$group_name</td>
                                    <td><input type='checkbox' name='check_group[]' value='$each_group_id'></td>                  
                     
                            </tr>";

                
                        
                    
                }
                    ?>
                </table>
                </div>
                <div class="form-group">
                    
                    <?php

                    /*foreach ($groups as $group) {
                        # code...
                        $group_name = $group['group_name'];
                        $each_group_id = $group['group_id'];
                        //$chat_title = $record['chat_title'];                        
                            //$chat_description = $record['chat_description'];

                        echo "
		                <label for='description'>$group_name:</label>
		                <span style='margin-left: 193px;margin-right: 60px'><input type='checkbox' id='checkAllREADgroup'>Check All</span>

		                    <span >
		                     <input type='checkbox' id='checkAllWRITEgroup'>Check All   
		                    </span>
		                <table class='table table-striped table-bordered' id='mytable_group'>
		                    <tr>
		                        <th>Group Name</th>
		                        <th>Read</th>
		                        <th>Write</th>             
		                    </tr>";

		                $stmnt = $conn->prepare("SELECT * FROM tbl_group_members WHERE group_id=:each_group_id");
					    $stmnt->bindParam(':each_group_id',$each_group_id);
					    $stmnt->execute();
					    $members = $stmnt->fetchAll();

					    foreach ($members as $member) {
					    	# code...
					    
					    	$member_user_id = $member['user_id'];
					    	$access_right = $member['access_right'];

                            echo "
                                <tr>
                                    <td>$member_user_id</td>
                                    <td><input type='checkbox' name='group_perm_read[]' value='$member_user_id'></td>                  
                                    <td><input type='checkbox' name='group_perm_write[]' value='$member_user_id'></td>                 
                                </tr>
                                
                                ";
                        }
                    
                    echo "</table>";
                }*/
                    ?>
                
                </div>
                <br>
                <div class="form-group">
                    <input type="submit" name="submit" value="Add" class="btn btn-primary">                       
                </div>


        
            </form>
        </div>
    </div>
<script type="text/javascript">
    $("#checkAllREAD").click(function () {
     $("#mytable tr td:nth-child(2) input[type=checkbox]").not(this).prop('checked', this.checked);

 });

    $("#checkAllWRITE").click(function () {
     $("#mytable tr td:nth-child(3) input[type=checkbox]").not(this).prop('checked', this.checked);

 });
    

    $("#checkAllREADgroup").click(function () {
     $("#mytable_group tr td:nth-child(2) input[type=checkbox]").not(this).prop('checked', this.checked);

 });

    $("#checkAllWRITEgroup").click(function () {
     $("#mytable_group tr td:nth-child(3) input[type=checkbox]").not(this).prop('checked', this.checked);

 });

</script>
</body>
</html>
