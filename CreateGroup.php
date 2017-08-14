<?php session_start(); ?>
<?php
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php");
    }
    include('config.php');  
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if(isset($_POST['submit']))
  {

    $group_name = $_POST['group_name'];
    $group_description = $_POST['group_description'];
    
    $stmnt = $conn->prepare("INSERT INTO tbl_group (group_name,group_description,group_creator,group_creation_date)values(:group_name,:group_description,:group_creator,:group_creation_date)");
    //description
    $stmnt->bindParam(':group_name',$group_name);
    $stmnt->bindParam(':group_description',$group_description);
    $stmnt->bindParam(':group_creator',$group_creator);
    $stmnt->bindParam(':group_creation_date',$group_creation_date);
    
    $group_creator=$_SESSION['user_id'];
    $group_creation_date = date('Y-m-d H:i:s');
    
    $stmnt->execute();

    $stmnt = $conn->prepare("SELECT group_id FROM tbl_group WHERE group_creator=:group_creator AND group_creation_date=:group_creation_date");
    $stmnt->bindParam(':group_creator',$group_creator);
    $stmnt->bindParam(':group_creation_date',$group_creation_date);
    $stmnt->execute();
    $records = $stmnt->fetchAll();

    foreach ($records as $record) 
    {
      # code...
      $group_id = $record['group_id'];
    }

        if (!empty($_POST['perm_write'])) 
        {
            $perm_write = $_POST['perm_write'];

            foreach ($perm_write as $id) 
            {
            
                $stmnt = $conn->prepare("INSERT INTO tbl_group_members (group_id,access_right,user_id)values(:group_id,:access_right,:user_id)");

                $stmnt->bindParam(':group_id',$group_id);
                $stmnt->bindParam(':access_right',$access_right);
                $stmnt->bindParam(':user_id',$id);
                $access_right=1;
                $stmnt->execute();
            }

        }

        if (!empty($_POST['perm_read'])) 
        {
            $perm_read = $_POST['perm_read'];
            $perm_write = array();
            if(isset($_POST['perm_write']))
            $perm_write = $_POST['perm_write'];
            foreach ($perm_read as $id) 
            {         

                if (!in_array($id, $perm_write)) 
                {
                    # code...
                    $stmnt = $conn->prepare("INSERT INTO tbl_group_members (group_id,access_right,user_id)values(:group_id,:access_right,:user_id)");
            
                        $stmnt->bindParam(':group_id',$group_id);
                        $stmnt->bindParam(':access_right',$access_right);
                        $stmnt->bindParam(':user_id',$id);                        
                        $access_right=0;
                        $stmnt->execute();
                }
            

            }
        }   

    $conn = null;
    echo '<script>alert("Group Created Successfully")</script>';
    echo "<script>window.location.href='ListGroups.php'</script>";
  }

    $stmnt = $conn->prepare("SELECT * FROM tbl_user");
    $stmnt->execute();
    $records = $stmnt->fetchAll();
    $conn = null;
       
?>


<html lang="en">
<head>
<?php include 'Head.php'; ?>
</head>
<body class="main-body">
<nav class="navbar navbar-inverse navbar-static-top marginBottom-0" role="navigation">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
              <a class="navbar-brand" href="#" target="_blank">Sanskriti School</a>
            </div>
            
            <div class="collapse navbar-collapse" id="navbar-collapse-1">
                <ul class="nav navbar-nav">
                    
                    
                    <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Messages <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="Messages.php">List Messages</a></li>
                            
                            <li><a href="ListMessages.php">My Messages</a></li>
                            

                        </ul>
                    </li>
                    <li class="dropdown"><a href="ListUsers.php" >Users</a>
                        <!--<ul class="dropdown-menu">
                            <li><a href="ListUsers.php">List Users</a></li>
                            
                        </ul>-->
                    </li>
                    <li class="dropdown active"><a href="ListGroups.php" >Groups</a>
                        <!--<ul class="dropdown-menu">
                            <li><a href="ListGroups.php">List Groups</a></li>
                            
                        </ul>-->
                    </li>
                    <li><a href="Logout.php">Logout</a></li>
                    
                </ul>
            </div><!-- /.navbar-collapse -->
        </nav>
<div class="container">
    <h2 class="lead">Post Message</h2>
            
    <div class="row">
        <div class="col-sm-6">
            <form id="myform" method="POST" action="" data-toggle="validator" role="form">
                
                <div class="form-group">
                    <label for="title">Enter Group Name:</label>
                    <input type="text" name="group_name" class="form-control" id="pressnotename" required>
                </div>
                <div class="form-group">
                    <label for="description">Enter Group Description:</label>
                    <textarea name="group_description" class="form-control" id="description"></textarea>
                </div>
                                            
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
                    </tr>
                    
                    <?php

                    foreach ($records as $record) {
                        # code...
                        $name = $record['name'];
                        $each_user_id = $record['user_id'];
                        //$chat_title = $record['chat_title'];                        
                            //$chat_description = $record['chat_description'];

                            echo "
                                <tr>
                                    <td>$name</td>
                                    <td><input type='checkbox' name='perm_read[]' value='$each_user_id'></td>                  
                                    <td><input type='checkbox' name='perm_write[]' value='$each_user_id'></td>                 
                                </tr>";

                            echo "
                                    <input type='hidden' name='each_user_id[]' value='$each_user_id'>
                            ";
                        
                    
                }
                    ?>
                </table>
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

</script>
</body>
</html>
