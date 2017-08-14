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
    
    $stmnt = $conn->prepare("SELECT * from tbl_user");
    $stmnt->execute();
    $records = $stmnt->fetchAll();
    $conn = null;
?>

<html lang="en">
<head>
<?php include 'Head.php'; ?>
</head>
<body class="main-body">
<?php include 'UserHeader.php'; ?>
<div class="container">
    <h2 >Users List</h2>
    <div class="pull-right form-group">
    <button type="button" class="btn btn-primary" onclick="window.location.href='CreateUser.php'">Create New User</button>
    </div>       
    <div class="row">
        <div class="col-sm-12">
        	<table class="table table-striped table-bordered">
                    <tr>
                        <th>Name</th>
                        <th>User Name</th>
                        <th>Email ID</th> 
                        <th>Modify</th>
                        <th>Delete</th>            
                    </tr>
                    <?php

                    foreach ($records as $record) {
                        # code...
                        $name = $record['name'];
                        $username = $record['username'];                        
                            $email = $record['email'];
                            $user_id = $record['user_id'];

                            echo "
                                <tr>
                                    <td>$name</td>
                                    <td>$username</td>                  
                                    <td>$email</td>
                                    <td><a href='UpdateUser.php?modify_user_id=$user_id'>Modify</a></td>
                                    <td><a href='DeleteUser.php?modify_user_id=$user_id'>Delete</a></td>                
                                </tr>";

                        
                        
                    
                }
                    ?>
                </table>
      	</div>
    </div>

</body>
</html>