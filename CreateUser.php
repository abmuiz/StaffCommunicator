<?php
session_start();
?>
<?php
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php");
    }
    include('config.php');  
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_POST['submit'])) {
        # code...

        $stmnt = $conn->prepare("INSERT INTO tbl_user(name,email,mobile_no,username,password) VALUES(:name,:email,:mobile_no,:username,:password)");
        
        $stmnt->bindParam(':name',$name);
        $stmnt->bindParam(':email',$email);
        $stmnt->bindParam(':mobile_no',$mobile_no);
        $stmnt->bindParam(':username',$username);
        $stmnt->bindParam(':password',$password);
        

        $name = $_POST['name'];
        $email = $_POST['email'];        
        $mobile_no = $_POST['mobile_no'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $stmnt->execute();
        $conn = null;

        echo "<script>alert('User Added Successfully');</script>";
        echo "<script>window.location.href='ListUsers.php'</script>";

    }
   
?>


<html lang="en">
<head>
<?php include 'Head.php'; ?>
</head>
<body class="main-body">
<?php include 'UserHeader.php'; ?>
<div class="container">
    <h2 class="lead">Post Message</h2>
            
    <div class="row">
        <div class="col-sm-6">
            <form id="myform" method="POST" action="" data-toggle="validator" role="form">
                
                <div class="form-group">
                    <label for="title">Enter Name:</label>
                    <input type="text" name="name" class="form-control" id="pressnotename" required>
                </div>
                <div class="form-group">
                    <label for="description">Enter Email Id:</label>
                    <input type="text" name="email" class="form-control" id="description">
                </div>
                <div class="form-group">
                    <label for="description">Enter Mobile No.:</label>
                    <input type="text" name="mobile_no" class="form-control" id="description" maxlength="10">
                </div> 
                <div class="form-group">
                    <label for="description">Enter Username:</label>
                    <input type="text" name="username" class="form-control" id="description">
                </div>  
                <div class="form-group">
                    <label for="description">Enter Password:</label>
                    <input type="password" name="password" class="form-control" id="description">
                </div>
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
