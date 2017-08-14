<?php session_start(); ?>

<?php
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php");
    }
    
    $priviledge = $_SESSION['priviledge'];
    $user_id=$_SESSION['user_id'];

    include('config.php');  
    
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmnt = $conn->prepare("SELECT DISTINCT H.chat_header_id,chat_title,chat_description,chat_creator,creation_date,message_type,chat_read FROM tbl_chat_header H INNER JOIN tbl_access A ON H.chat_header_id=A.chat_header_id where user_id=:user_id AND override_flag!=1 ORDER BY creation_date DESC ");
    
    $stmnt->bindParam(':user_id',$user_id);
    $stmnt->execute();
    $records = $stmnt->fetchAll();
    
?>

<html lang="en">
<?php include 'Head.php'; ?>
</head>
<body class="main-body">
<?php
include ("MessageHeader.php");
?>
<div class="container">
    <h2 >Messages</h2>
    <hr>
    <div class="row">
        <div class="col-sm-12">


        <input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id; ?>">
            <div id="screen" class="panel panel-default">
            <?php
                echo    '<table class="table table-hover" id="mytable">';
                    
                    

                    foreach ($records as $record) {
                        # code...
                        $chat_header_id = $record['chat_header_id'];
                        $chat_title = $record['chat_title'];                        
                        $chat_description = $record['chat_description'];
                        $chat_creator = $record['chat_creator'];
                        $creation_date = $record['creation_date'];
                        
                        $check_date = date('Y-m-d', strtotime( $creation_date ));
                        
                        if($check_date==date("Y-m-d"))
                            $creation_date = date('H:i:s', strtotime( $creation_date ));
                        else
                            $creation_date = date('d/m/Y', strtotime( $creation_date ));   
                        
                        $message_type = (int)$record['message_type'];
                        if($message_type==1)
                        {
                            $star_color = 'green';
                            $type_name = 'Parent Circular';
                        }

                        elseif($message_type==2)
                        {
                            $star_color = 'cream';
                            $type_name = 'Group Message';
                        }


                        elseif($message_type==3)
                        {
                            $star_color = 'yellow';
                            $type_name = 'Individual Message';
                        }

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
                                <tr id='$chat_header_id' class='clickable-row read' data-href='Chat.php?chat_header_id=".urlencode($chat_header_id)."'>
                                    <td><input type='checkbox' ></td>
                                    <td><span class=$star_color>&#9733;</span></td>
                                    
                                    <td >$name</td>
                                    <td style=''>$chat_title<p class='description'>&nbsp- $chat_description</p></td>
                                    <td >$type_name</td>                  
                                    <td >$creation_date</td>
                                </tr>";
                            }
                            else
                            {
                            echo "
                                <tr id='$chat_header_id' class='clickable-row unread' data-href='Chat.php?chat_header_id=".urlencode($chat_header_id)."'>
                                    <td><input type='checkbox'   ></td>
                                    <td><span class=$star_color>&#9733;</span></td>
                                    
                                    <td ><b>$name</b></td>
                                    <td ><b>$chat_title</b><span class='description'>&nbsp- $chat_description</span></td>
                                    <td ><b>$type_name</b></td>                  
                                    <td ><b>$creation_date</b></td>
                                </tr>";
                            }

                                         
                                            
                }
                   
                echo '</table>';
                ?>
                
            </div>
        </div>
    </div>

<script type="text/javascript">

function update()
{

    var row = $("#mytable").closest('table').find('tr:first').attr('id');
    $.ajax({
        url:"MessagesAjax.php",
        type:"POST",
        data: {
            user_id: $("#user_id").val(),
            last_row:row
        },

       

        success : function(result){

            if (result!="") {
                var obj = JSON.parse(result);            
                var type_name='';
                
                for(i in obj)
                {
                    

                    var link = 'Chat.php?chat_header_id='+obj[i].chat_header_id;
                    var tr = $('<tr />').attr({'class' : 'clickable-row unread' , 'data-href' : link , 'id' : obj[i].chat_header_id});
                    var td0 = $('<td />');

                    if(obj[i].message_type==1)
                    {
                    var span = $('<span />').html('&#9733;').attr({'class':'green'});
                    type_name = 'Parent Circular';
                    }
                    if(obj[i].message_type==2)
                    {
                    var span = $('<span />').html('&#9733;').attr({'class':'cream'});
                    type_name = 'Group Message';
                    }
                    if(obj[i].message_type==3)
                    {
                    var span = $('<span />').html('&#9733;').attr({'class':'yellow'});
                    type_name = 'Individual Message';
                    }
                    td0.append(span);

                    var td1 = $('<td />');
                    var checkbox = $('<input />').attr({'type' : 'checkbox', 'name' : 'check'});
                    td1.append(checkbox);
                    var td2 = $('<td />').html('<b>'+obj[i].chat_creator+'</b>');
                    var desc = $('<span />').html('<br>'+obj[i].chat_description).attr({'class' : 'description'});
                    var td3 = $('<td />').html('<b>'+obj[i].chat_title+'</b>');
                    td3.append(desc);
                    var td4 = $('<td />').html('<b>'+type_name+'</b>');
                    var td5 = $('<td />').html('<b>'+obj[i].creation_date+'</b>');
                    tr.append(td1,td0,td2,td3,td4,td5);
                    $('#mytable').prepend(tr);
                }                
            }            
        }       
    });

    setTimeout('update()', 1000);  
    return ;
}
 



$(document).ready(
 
function() {
    
    setTimeout('update()', 10000);    

    $('#screen').on("click",'#mytable td:not(:nth-child(2),:nth-child(1))',function(){

        window.location.href = $(this).closest('tr').data("href");

    });
    
}

);

</script>
</body>
</html>
<?
$conn = null;
?>



