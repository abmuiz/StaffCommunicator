<?php session_start(); ?>

<?php

    $priviledge = $_SESSION['priviledge'];

    if (!isset($_SESSION['user_id'])) {
        # code...
        header("Location: index.php");
    }

    $user_id=$_SESSION['user_id'];

    include('config.php');  
    
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmnt = $conn->prepare("SELECT DISTINCT H.chat_header_id,chat_title,chat_description,chat_creator,creation_date,message_type,chat_read FROM tbl_chat_header H INNER JOIN tbl_access A ON H.chat_header_id=A.chat_header_id where user_id=:user_id ORDER BY creation_date DESC");
    
    $stmnt->bindParam(':user_id',$user_id);
    $stmnt->execute();
    $records = $stmnt->fetchAll();
    
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
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<!-- Generic page styles -->
<link rel="stylesheet" href="css/ExternalStyle.css">
<!-- blueimp Gallery styles -->

<!-- CSS adjustments for browsers with JavaScript disabled -->
<style type="text/css">
    .clickable-row {
    cursor: pointer;

}

.read
{
    background-color: rgba(240, 240, 240,0.3);

}

.unread
{
    background-color: rgba(209,209,209,0.3);
    
    
}

  </style>
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
                    
                <?php

                    echo '
                    <li class="dropdown active"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Messages <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li ><a href="#">List Messages</a></li>
                            
                            <li><a href="ListMessages.php">My Messages</a></li>
                            

                        </ul>
                    </li>';

                    if($priviledge<2)
                    echo '
                    <li class="dropdown "><a href="ListUsers.php" >Users</a>
                        <!--<ul class="dropdown-menu">
                            <li><a href="ListUsers.php">List Users</a></li>
                            
                        </ul>-->
                    </li>';

                    if($priviledge<=2)
                    echo '
                    <li class="dropdown "><a href="ListGroups.php" >Groups</a>
                        <!--<ul class="dropdown-menu">
                            <li><a href="ListGroups.php">List Groups</a></li>
                            
                        </ul>-->
                    </li>';

                    echo '
                    <li><a href="Logout.php">Logout</a></li>
                    ';
                    ?>
                    
                </ul>
            </div><!-- /.navbar-collapse -->
        </nav>
<div class="container">
    <h2 class="lead">Messages</h2>
            
    <div class="row">
        <div class="col-sm-12">


        <input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id; ?>">
            <div id="screen">
            <?php
                echo    '<table class="table table-hover" id="mytable">';
                    
                    

                    foreach ($records as $record) {
                        # code...
                        $chat_header_id = $record['chat_header_id'];
                        $chat_title = $record['chat_title'];                        
                        $chat_description = $record['chat_description'];
                        $chat_creator = $record['chat_creator'];
                        $creation_date = $record['creation_date'];
                        $creation_date = date_create($creation_date);
                        $creation_date = date_format($creation_date,"d/m/Y H:i:s");
                        $message_type = $record['message_type'];
                        $chat_read = (int)$record['chat_read'];
                        $stmnt = $conn->prepare("SELECT name from tbl_user WHERE user_id=:chat_creator");
                        $stmnt->bindParam(':chat_creator',$chat_creator);
                        $stmnt->execute();
                        $user_data = $stmnt->fetchAll();  

                        foreach ($user_data as $value) {
                          $name = $value['name'];
                        }     

                            
                            if($chat_read==1)
                            {
                            echo "
                                <tr id='$chat_header_id'>
                                    <td><input type='checkbox'  name='read'  ></td>
                                    <td class='clickable-row' data-href='Chat.php?chat_header_id=$chat_header_id'>$name</td>
                                    <td class='clickable-row' data-href='Chat.php?chat_header_id=$chat_header_id'>$chat_title<br>$chat_description</td>
                                    <td class='clickable-row' data-href='Chat.php?chat_header_id=$chat_header_id'>$message_type</td>                  
                                    <td class='clickable-row' data-href='Chat.php?chat_header_id=$chat_header_id'>$creation_date</td>
                                </tr>";
                            }
                            else
                            {
                            echo "
                                <tr id='$chat_header_id'>
                                    <td><input type='checkbox'  name='read' ></td>
                                    <td class='clickable-row' data-href='Chat.php?chat_header_id=$chat_header_id'><b>$name</b></td>
                                    <td class='clickable-row' data-href='Chat.php?chat_header_id=$chat_header_id'><b>$chat_title</b><br>$chat_description</td>
                                    <td class='clickable-row' data-href='Chat.php?chat_header_id=$chat_header_id'><b>$message_type</b></td>                  
                                    <td class='clickable-row' data-href='Chat.php?chat_header_id=$chat_header_id'><b>$creation_date</b></td>
                                </tr>";
                            }

                                         
                                            
                }
                   
                echo '</table>';
                ?>
                
            </div>
            <button id="but">click</button>
            <textarea rows="20" cols="20" id="tt"></textarea>
        </div>
    </div>

<script type="text/javascript">

function update()
{

    var row = $("#mytable").closest('table').find('tr:first').attr('id');

    $.ajax({
        url:"test.php",
        
    });


  $.post("MessagesAjax.php",
    {
        user_id: $("#user_id").val(),
        last_row:row
    },
    function(data, status){
        //alert("Data: " + data + "\nStatus: " + status);
        if(data!="")
            $("#tt").val(data);
        //alert(data);
        //$("#mytable").prepend(data);
        //document.getElementById('screen').innerHTML = data;
    });
 
    setTimeout('update()', 10000);  
    return ;
}
 



$(document).ready(
 
function() {
    
    
    
        update();


    $("#screen").on("click",".clickable-row", function(){
        var row = $(this).closest('table').find('tr:first').attr('id');
        alert(row);
        //window.location = $(this).data("href");
    });

    $('#but').on('click', function(e){
    $('#mytable').prepend('<tr><td>newcol1</td><td>newcol2</td><td>newcol3</td></tr><tr><td>newcol10</td><td>newcol10</td><td>newcol10</td></tr>');
    });
    
}

);

</script>
</body>
</html>
<?
$conn = null;
?>