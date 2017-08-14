<?php session_start(); ?>
<?php 
	
	if (!isset($_SESSION['user_id'])) {
        header("Location: index.php");
    }
    include('config.php'); 
	$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    
    $chat_creator = $_SESSION['user_id'];
    $chat_title = $_POST['chat_title'];
    $chat_description = $_POST['chat_description'];
	$message_type = (int)$_POST['message_type'];

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
		return $records;
	}

		catch(PDOException $e)
		{
		echo "Error: " . $e->getMessage();
		}

	}
	
    if (isset($_POST['submit_members'])) {
    	
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
	    
	      $chat_header_id = $record['chat_header_id'];
		}

	    $stmnt = $conn->prepare("INSERT INTO tbl_access (chat_header_id,access_right,user_id,group_id)values(:chat_header_id,:access_right,:user_id,0)");
	            //description
	    $stmnt->bindParam(':chat_header_id',$chat_header_id);
	    $stmnt->bindParam(':access_right',$access_right);
	    $stmnt->bindParam(':user_id',$chat_creator);
	    $access_right=1;
	    $stmnt->execute();
	    
	    if(!empty($_POST['perm_write']))
	    {
	    	$perm_write = $_POST['perm_write'];

	    	foreach($perm_write as $key => $grp_member_access)
	    	{
	    		//$perm_write = array();
		    
		        //$perm_write = array_unique($grp_member_access);
		        foreach ($grp_member_access as $id) 
		        {
		        	
		            if($id!=$chat_creator)
		            {
		            
		            $stmnt = $conn->prepare("INSERT INTO tbl_access (chat_header_id,access_right,user_id,group_id)values(:chat_header_id,:access_right,:user_id,:group_id)");
		            //description
		            $stmnt->bindParam(':chat_header_id',$chat_header_id);
		            $stmnt->bindParam(':access_right',$access_right);
		            $stmnt->bindParam(':user_id',$id);
		            $stmnt->bindParam(':group_id',$key);
		            $access_right=1;
		            $stmnt->execute();
		        	}
		        }

		    
			}
		}
		
	    if (!empty($_POST['perm_read'])) 
	    {
	    	$perm_read = $_POST['perm_read'];
	    	$perm_write = array();
	    	if(isset($_POST['perm_write']))
	    	$perm_write = $_POST['perm_write'];
	        //$perm_read = array_unique($_POST['perm_read']);
	        foreach ($perm_read as $key => $grp_member_access) 
	        {         

	        	foreach ($grp_member_access as $id) 
	        	{
	        		# code...
	        		if (array_key_exists($key, $perm_write)) 
	        		{
		        		if (!in_array($id, $perm_write[$key]) && $id!=$chat_creator) 
			            {
			                # code...
			                
			                $stmnt = $conn->prepare("INSERT INTO tbl_access (chat_header_id,access_right,user_id,group_id)values(:chat_header_id,:access_right,:user_id,:group_id)");

			                $stmnt->bindParam(':chat_header_id',$chat_header_id);
			                $stmnt->bindParam(':access_right',$access_right);
			                $stmnt->bindParam(':user_id',$id);
			                $stmnt->bindParam(':group_id',$key);
			                $access_right=0;
			                $stmnt->execute();
			            }
	        		}
	        		else
	        		{
	        			if($id!=$chat_creator)
		            	{
	        				$stmnt = $conn->prepare("INSERT INTO tbl_access (chat_header_id,access_right,user_id,group_id)values(:chat_header_id,:access_right,:user_id,:group_id)");

			                $stmnt->bindParam(':chat_header_id',$chat_header_id);
			                $stmnt->bindParam(':access_right',$access_right);
			                $stmnt->bindParam(':user_id',$id);
			                $stmnt->bindParam(':group_id',$key);
			                $access_right=0;
			                $stmnt->execute();
			            }
	        		}
		            
	            
	        	}
	        }
	    }   


	    $conn = null;
	    echo '<script>alert("Message Added Successfully")</script>';
	    echo "<script>window.location.href='ListMessages.php'</script>";				
    }

    
?>

<html lang="en">
<head>
<?php include 'Head.php'; ?>
<style type="text/css">
.readonly_access
{
	pointer-events: none;
}
</style>
</head>
<body class="main-body">
<?php include 'MessageHeader.php'; ?>
<div class="container">
    <h2 class="lead">Select Users for Message</h2>
            
    <div class="row">
        <div class="col-sm-6">
            <form id="myform" method="POST" action="CreateMessageNext.php" data-toggle="validator" role="form">
              <input type="hidden" name="chat_title" value="<?php echo $chat_title; ?>">
              <input type="hidden" name="chat_description" value="<?php echo $chat_description; ?>">
              <input type="hidden" name="message_type" value="<?php echo $message_type; ?>"> 
               
               <?php 
               if(isset($_POST['all_users']))
               {
               	$stmnt = $conn->prepare("SELECT * FROM tbl_user");
			    $stmnt->execute();
			    $records = $stmnt->fetchAll();
               echo '
                <div class="form-group">
                <label for="description">Select Users for Message:</label>
                <span style="margin-right: 60px;margin-left: 104px"><input type="checkbox" id="checkAllREAD">Check All</span>

                    <span >
                     <input type="checkbox" id="checkAllWRITE">Check All   
                    </span>
                <table class="table table-striped table-bordered" id="mytable">
                    <tr>
                        <th>User Name</th>
                        <th>Read</th>
                        <th>Write</th>             
                    </tr>';


                    foreach ($records as $record) {
                        # code...
                        $name = $record['name'];
                        $each_user_id = $record['user_id'];

                        
                        echo "
                            <tr>
                                    <td>$name</td>
                                    <td><input type='checkbox' name='perm_read[0][]' value='$each_user_id'></td>                  
                                    <td><input type='checkbox' name='perm_write[0][]' value='$each_user_id'></td>                 
                                </tr>";                            
                    
                	}

                	echo '</table>
               			 </div>';

               	}
                    ?>
                

                
                    
                    <?php

                    if(isset($_POST['check_group']))
                     {
                     	$check_group = $_POST['check_group'];
                    $stmnt = $conn->prepare("SELECT * FROM tbl_group WHERE group_creator=:chat_creator");
				    $stmnt->bindParam(':chat_creator',$chat_creator);
				    $stmnt->execute();
				    $groups = $stmnt->fetchAll();
                    foreach ($groups as $group) {
                        # code...

                        $group_name = $group['group_name'];
                        $each_group_id = $group['group_id'];
                        //$chat_title = $record['chat_title'];                        
                            //$chat_description = $record['chat_description'];
                        
                        if(in_array($each_group_id, $check_group))
                        {
                        echo "
                        <div class='form-group'>
		                <label for='description'>$group_name:</label>
		               
		                <table class='table table-striped table-bordered' id='mytable_group'>
		                    <tr>
		                        <th>Member Name</th>
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
					    	$access_right = (int)$member['access_right'];
					    	$names = select_tbl_user($member_user_id);
					    	foreach ($names as $name) {
					    		# code...
					    		$member_name = $name['name'];
					    	}

					    	if($access_right==1)
                            echo "
                                <tr>
                                    <td>$member_name</td>
                                    <td><input type='checkbox' name='perm_read[$each_group_id][]' value='$member_user_id' class='readonly_access' readonly></td>                  
                                    <td><input type='checkbox' name='perm_write[$each_group_id][]' value='$member_user_id' checked class='readonly_access' readonly></td>                 
                                </tr>
                                
                                ";

                            else
                            echo "
                                <tr>
                                    <td>$member_name</td>
                                    <td><input type='checkbox' name='perm_read[$each_group_id][]' value='$member_user_id' checked class='readonly_access' readonly></td>                  
                                    <td><input type='checkbox' name='perm_write[$each_group_id][]' value='$member_user_id' class='readonly_access' readonly></td>                 
                                </tr>
                                
                                ";
                        }
                    
                    echo "</table>
                    </div>";
                	}
                  }
                }
                    ?>
                
                
                <br>
                <div class="form-group">
                    <input type="submit" name="submit_members" value="Submit" class="btn btn-primary">                       
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
