<?php
session_start();
?>

<?php
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php");
    }
    $user_id=$_SESSION['user_id'];

    include('config.php');  
    
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmnt = $conn->prepare("SELECT * from tbl_group WHERE group_creator=:user_id");
    $stmnt->bindParam('user_id',$user_id);
    $stmnt->execute();
    $records = $stmnt->fetchAll();
    $conn = null;
?>

<html lang="en">
<head>
<?php include 'Head.php'; ?>
</head>
<body class="main-body">
<?php include 'GroupHeader.php'; ?>
<div class="container">
    <h2 >Group List</h2>
    <div class="pull-right form-group">
    <button type="button" class="btn btn-primary" onclick="window.location.href='CreateGroup.php'">Create New Group</button>
    </div>                
    <div class="row">
        <div class="col-sm-12">
        	
                    <?php

                    if(!empty($records))
                    {

                    echo '<table class="table table-striped table-bordered">
                        <tr>
                            <th>Group Name</th>
                            <th>Group Description</th>
                            <th>Group Creation Date</th> 
                            <th>Modify</th>
                            <th>Delete</th>            
                        </tr>';
                    foreach ($records as $record) {
                        # code...

                        $group_name = $record['group_name'];
                        $group_description = $record['group_description'];
                        $group_creation_date = $record['group_creation_date'];
                        $group_creation_date = date("d/m/Y",strtotime($group_creation_date));
                        $group_id = (int)$record['group_id'];
                        if($group_id!=0)
                            echo "
                                <tr>
                                    <td>$group_name</td>
                                    <td>$group_description</td>                  
                                    <td>$group_creation_date</td>
                                    <td><a href='DeleteGroup.php?group_id=$group_id'>Delete</a></td>                
                                    <td><a href='UpdateGroup.php?group_id=$group_id'>Modify</a></td>
                                </tr>";

                        
                        
                    
                }
            }
                    ?>
                </table>
      	</div>
    </div>

</body>
</html>