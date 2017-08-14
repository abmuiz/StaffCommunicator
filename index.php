<?php session_start(); ?>

<?php

  
if(isset($_POST['submit']))
{
   
  try
  {
    include('config.php');  
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    $stmnt = $conn->prepare("SELECT * FROM tbl_user WHERE username=:username AND password=:password");
    $stmnt->bindParam(':username',$username);
    $stmnt->bindParam(':password',$password);
    $username=$_POST['username'];
    $password=$_POST['password'];
    $stmnt->execute();
    $records = $stmnt->fetchAll();
    $conn = null;
    if($records==NULL)
    {

      echo '<script>alert("Wrong username or password, please enter correct Credentials")</script>';
    }
    
    else
    { 
    
      foreach($records as $record)
        {
            $admin =(int)$record['admin'];
            $user_id = (int)$record['user_id'];
            $name = $record['name'];
            $priviledge = (int)$record['priviledge'];
        }

       
        //$_SESSION['Name']=$Name;

        $_SESSION['name']=$name;
        $_SESSION['user_id']=$user_id;
        $_SESSION['priviledge'] = $priviledge;
          
          echo "<script type='text/javascript'>window.location.href ='Messages.php'</script>";
    }
      

  }     

  catch(PDOException $e)
  {
    echo "Error: " . $e->getMessage();
  }

}



?>


<html lang="en">
<head>
<!-- Force latest IE rendering engine or ChromeFrame if installed -->
<!--[if IE]>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<![endif]-->
<meta charset="utf-8">
<title>Staff Communicator</title>
<meta name="description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap styles -->
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
<!-- Generic page styles -->
<link rel="stylesheet" href="css/ExternalStyle.css">

<link rel="stylesheet" href="validate/style.css">
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
              <a class="navbar-brand" href="#">Sanskriti School</a>
            </div>
            
            
        </nav>
  <div class="container">
    <div class="row">
      <div class="col-sm-4 panel panel-default">
        <form id="myform" method="POST" action="" data-toggle="validator" role="form">
          
          <h2 class="lead">Enter Your Name and password</h2>
            <div class="form-group">
              <label for="username">User Name:</label>
              <input type="text" name="username" id="username" class="form-control" required>
            </div>
                    
            <div class="form-group">
              <label for="password">password:</label>
              <input type="password" name="password" class="form-control" id="password" required>
            </div>
                    
            <div class="form-group">
              <input type="submit" name="submit" value="Login" class="btn btn-primary">
            </div>
          
        </form>
      </div>
    </div>
  </div>        

</div>

</body>
</html>
