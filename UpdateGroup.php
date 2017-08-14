<?php session_start();  ?>

<?php
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php");
    }
    include('config.php');  
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    
    if (isset($_POST['submit'])) 
    {
        # code...
        $stmnt = $conn->prepare("UPDATE tbl_group SET group_name=:group_name,group_description=:group_description where group_id=:group_id");

    
        $stmnt->bindParam(':group_name',$group_name);
        $stmnt->bindParam(':group_description',$group_description);
        $stmnt->bindParam(':group_id',$group_id);
        

        $group_name = $_POST['group_name'];
        $group_description = $_POST['group_description'];
        $group_id = $_POST['group_id'];        
        $stmnt->execute();

        $stmnt = $conn->prepare("DELETE FROM tbl_group_members WHERE group_id=:group_id");
        $stmnt->bindParam(':group_id',$group_id);        
        $stmnt->execute();

        if (!empty($_POST['perm_write'])) 
        {
            $perm_write = $_POST['perm_write'];
            foreach ($perm_write as $id) {
            # code...

            $stmnt = $conn->prepare("INSERT INTO tbl_group_members (group_id,access_right,user_id)values(:group_id,:access_right,:user_id)");
        //description
                    $stmnt->bindParam(':group_id',$group_id);
                    $stmnt->bindParam(':access_right',$access_right);
                    $stmnt->bindParam(':user_id',$id);
                    //$stmnt->bindParam(':creation_date',$creation_date);
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

        echo '<script>alert("Group Details Updated Successfully")</script>';
        echo "<script type='text/javascript'>window.location.href ='ListGroups.php'</script>";
    }

        $stmnt = $conn->prepare("SELECT * FROM tbl_group WHERE group_id=:group_id");
        $stmnt->bindParam(':group_id',$group_id); 
        $group_id = $_GET['group_id'];
        $stmnt->execute();
        $records = $stmnt->fetchAll();
        foreach ($records as $record) 
        {
            $group_name=$record['group_name'];
            $group_description=$record['group_description']; 
        }

        $stmnt = $conn->prepare("SELECT * FROM tbl_group_members WHERE group_id=:group_id");
        $stmnt->bindParam(':group_id',$group_id); 
        
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
<head>
<?php include 'Head.php'; ?>
</head>
<body class="main-body">
<?php include 'GroupHeader.php'; ?>
<div class="container">
    <h2 class="lead">Modify Group Details</h2>
    <div class="row">
        <div class="col-sm-6">

    <form id="myform" method="POST" action="" data-toggle="validator" role="form">
        
                    <div class="form-group">
                        <label for="title">Modify Group Name:</label>
                        <input type="text" name="group_name" class="form-control" id="pressnotename" value="<?php echo $group_name;?>" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Modify Group Description:</label>
                        <input type="text" name="group_description"  class="form-control" id="description" value="<?php echo $group_description;?>">
                    </div>
                    
                    <label for="description">Select Users for Group:</label>
                    
                    
                    <span style="margin-right: 57px;margin-left: 120px"><input type="checkbox" id="checkAllREAD">Check All</span>

                    <span >
                     <input type="checkbox" id="checkAllWRITE">Check All   
                    </span>

                    <?php

                    if($records!=NULL)
                    {
                    echo '
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
                        //$group_name = $record['group_name'];                        
                            //$group_description = $record['group_description'];
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
                }
                    ?>
                </table>
              
              <input type="hidden" name="group_id" value="<?php echo $group_id; ?>">
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
<?php
$conn = NULL;
?>