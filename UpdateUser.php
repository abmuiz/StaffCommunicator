<?php session_start(); ?>
<?php
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php");
    }
    include('config.php');  
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $modify_user_id = $_GET['modify_user_id'];
    
    if (isset($_POST['submit'])) 
    {
        # code...
        $stmnt = $conn->prepare("UPDATE tbl_user SET name=:name,email=:email,mobile_no=:mobile_no,username=:username,password=:password where user_id=:modify_user_id");

    
        $stmnt->bindParam(':name',$name);
        $stmnt->bindParam(':email',$email);
        $stmnt->bindParam(':mobile_no',$mobile_no);
        $stmnt->bindParam(':username',$username);
        $stmnt->bindParam(':password',$password);
        $stmnt->bindParam(':modify_user_id',$modify_user_id);

        $name = $_POST['name'];
        $email = $_POST['email'];
        $mobile_no = $_POST['mobile_no'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $modify_user_id = $_POST['modify_user_id'];
        
        $stmnt->execute();

        echo '<script>alert("User Details Updated Successfully")</script>';
        echo "<script type='text/javascript'>window.location.href ='ListUsers.php'</script>";
    }

        $stmnt = $conn->prepare("SELECT * FROM tbl_user WHERE user_id=:modify_user_id");
        $stmnt->bindParam(':modify_user_id',$modify_user_id);        
        $stmnt->execute();
        $records = $stmnt->fetchAll();
        foreach ($records as $record) 
        {
                        # code...
                        $name=$record['name'];
                        $email=$record['email'];
                        $mobile_no=$record['mobile_no'];
                        $username=$record['username'];
                        $password=$record['password'];
                        
        }
  
?>


<html lang="en">
<?php include 'Head.php'; ?>
<body class="main-body">
<?php include 'UserHeader.php' ?>
<div class="container">
    <h2 class="lead">Modify User Details</h2>
    <div class="row">
        <div class="col-sm-6">

    <form id="myform" method="POST" action="" data-toggle="validator" role="form">
        
                    <div class="form-group">
                        <label >Modify Person Name:</label>
                        <input type="text" name="name"  class="form-control" value="<?php echo $name;?>">
                    </div>
                    <div class="form-group">
                        <label >Modify Email Id:</label>
                        <input type="text" name="email"  class="form-control" value="<?php echo $email;?>">
                    </div>
                    <div class="form-group">
                        <label >Modify Mobile No.:</label>
                        <input type="text" name="mobile_no"  class="form-control" value="<?php echo $mobile_no;?>">
                    </div>
                    <div class="form-group">
                        <label >Modify Usename:</label>
                        <input type="text" name="username"  class="form-control" value="<?php echo $username;?>">
                    </div>
                    <div class="form-group">
                        <label >Modify Password:</label>
                        <input type="password" name="password"  class="form-control" value="<?php echo $password;?>">
                    </div>  
                    <input type="hidden" name="modify_user_id" value="<?php echo $modify_user_id; ?>">              
                    <div class="form-group" >
                    <input type="submit" name="submit" value="Update" class="btn btn-primary">            
                    </div>
              
            
    </form>
    </div>
    </div>
</div>      
</body>
</html>