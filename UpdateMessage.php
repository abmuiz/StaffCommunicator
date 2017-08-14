<?php session_start();  ?>

<?php
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php");
    }

    include('config.php'); 
    include 'BasicFunctions.php';
    $_SESSION['chat_header_id'] = $_GET['chat_header_id'];

    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $chat_creator = $_SESSION['user_id'];

    if (isset($_POST['submit'])) 
    {
        # code...
        $stmnt = $conn->prepare("UPDATE tbl_chat_header SET chat_title=:chat_title,chat_description=:chat_description,message_type=:message_type where chat_header_id=:chat_header_id");

    
        $stmnt->bindParam(':chat_title',$chat_title);
        $stmnt->bindParam(':chat_description',$chat_description);
        $stmnt->bindParam(':message_type',$message_type);
        $stmnt->bindParam(':chat_header_id',$chat_header_id);
        

        $chat_title = $_POST['chat_title'];
        $chat_description = $_POST['chat_description'];
        $message_type = (int)$_POST['message_type'];
        $chat_header_id = $_POST['chat_header_id'];
        
        $stmnt->execute();

        echo '<script>alert("User Details Updated Successfully")</script>';
        echo "<script type='text/javascript'>window.location.href ='ListMessages.php'</script>";
    }
        $chat_header_id = $_GET['chat_header_id'];
        $records = get_chat_header_details($chat_header_id);
        
        foreach ($records as $record) 
        {
            $chat_title=$record['chat_title'];
            $chat_description=$record['chat_description'];
            $message_type=$record['message_type'];               
        }

        $stmnt = $conn->prepare("SELECT DISTINCT group_id FROM tbl_access WHERE chat_header_id=:chat_header_id");
        $stmnt->bindParam(':chat_header_id',$chat_header_id); 
        
        $stmnt->execute();
        $group_access = $stmnt->fetchAll();

        $stmnt = $conn->prepare("SELECT DISTINCT `user_id`,`access_right`,override_flag FROM tbl_access WHERE chat_header_id=:chat_header_id AND access_right=1"); 

        $stmnt->bindParam(':chat_header_id',$chat_header_id);

        $stmnt->execute();
        $write_members = $stmnt->fetchAll();
        $write_array=array();
        $write_override =array();
        for($i=0;$i<count($write_members);$i++)
        {
            $userid_index = $write_members[$i][0];
            $write_array[$i] = $userid_index;
            $write_override[$userid_index] = $write_members[$i][2];
        }

        $stmnt = $conn->prepare("SELECT DISTINCT `user_id`,`access_right`,override_flag FROM tbl_access WHERE chat_header_id=:chat_header_id AND access_right=0"); 

        $stmnt->bindParam(':chat_header_id',$chat_header_id);

        $stmnt->execute();
        $read_members = $stmnt->fetchAll();
        $read_array=array();
        $read_override= array();
        for($i=0;$i<count($read_members);$i++)
        {
            $userid_index = $read_members[$i][0];
            $read_array[$i] = $userid_index;
            $read_override[$userid_index] = $read_members[$i][2];
        }
        

        $array_diff = array_values(array_diff($read_array, $write_array));
?>


<html lang="en">
<head>
<?php include 'Head.php'; ?>
<style type="text/css">
    
input[type="checkbox"][readonly] {
  pointer-events: none;
}

.borderless {
    border: 0px;
}
</style>
<?php 

if (isset($_GET['action'])&&isset($_GET['permission_override']))
echo "<script>\$('#all_members').show();</script>";

?>
</head>
<body class="main-body">
<?php include 'MessageHeader.php'; ?>
 <div class="container">
 <h2 class="lead">Modify User Details</h2>
    <div class="row">
    <form id="myform" method="POST" action="" data-toggle="validator" role="form">
        <div class="col-sm-6">
        
        
            <div class="form-group">
                <label for="title">Modify Title:</label>
                <input type="text" name="chat_title" class="form-control" id="pressnotename" value="<?php echo $chat_title;?>" required>
            </div>
            <div class="form-group">
                <label for="description">Modify Description:</label>
                <input type="text" name="chat_description"  class="form-control" id="description" value="<?php echo $chat_description;?>">
            </div>
            <div class="form-group" >
                    <label>Select Type of Message</label>
                    <select name="message_type" class="form-control" >
                        <option value="1">Parent Circular</option>
                        <option value="2">Group Messages</option>
                        <option value="3">Individual Messages</option>
                    </select>
            </div>
            
            <div id="all_members" >
            <?php

                echo "
                <label>All Members of Message</lable>
                <table class='table table-striped table-bordered' id='modify_table'>
                <tr>
                    <th>Select</th>
                    <th>User Name</th>
                    <th>Permission</th>
                    <th>Overriden Permission</th>                    
                </tr>
                ";

                for ($i=0; $i < count($write_array); $i++) { 
                    # code...
                    $user_id = $write_array[$i];
                    
                    $name = get_name_byid($user_id);
                    echo "<tr>
                            <td><input type='checkbox' name='permission_override[]' value='$user_id' ></td>
                            <td>$name</td> 
                            <td>Read & Write</td>
                            ";
                    if($write_override[$user_id]==0)
                    echo "

                            <td></td>
                                         
                        </tr>";

                    elseif ($write_override[$user_id]==1)
                    echo "
                            
                            <td>Disabled</td>                 
                        </tr>";

                    elseif ($write_override[$user_id]==2)
                    echo "
                            <td>Readonly</td>
                                             
                        </tr>";

                    elseif ($write_override[$user_id]==3)
                    echo "
                            <td>Read & Write</td>                 
                        </tr>";

                }

                for ($i=0; $i < count($array_diff); $i++) { 
                    # code...
                    
                    
                    $user_id = $array_diff[$i];
                    $name = get_name_byid($user_id);
                    echo "<tr>
                            <td><input type='checkbox' name='permission_override[]' value='$user_id'></td>
                            <td>$name</td>
                            <td>Readonly</td>
                        ";

                    if($read_override[$user_id]==0)
                    echo "
                            <td></td>
                                            
                        </tr>";

                    elseif ($read_override[$user_id]==1)
                    echo "
                            <td>Disabled</td>                 
                        </tr>";

                    elseif ($read_override[$user_id]==2)
                    echo "
                            <td>Readonly</td>                 
                        </tr>";

                    elseif ($read_override[$user_id]==3)
                    echo "
                            <td>Read & Write</td>                 
                        </tr>";
                }

                

                echo "</table>";

                echo "<table class='table borderless' class='borderless'>";
                echo "<tr class='borderless'>
                        <td style='border:0'><input type='checkbox' id=checkAll>Check All</td>
                        <td style='border:0'>With selected: </td>
                        <td style='border:0'><button class='override_action' value='0'>Enable</button></td>
                        <td style='border:0'><button class='override_action' value='1'>Disable</button></td>
                        <td style='border:0'><button class='override_action' value='2'>Read</button></td>
                        <td style='border:0'><button class='override_action' value='3'>Write</button></td>
                        </tr>
                ";
                echo "</table>";
            ?>
            </div>
            <input type="hidden" name="chat_header_id" value="<?php echo $chat_header_id; ?>" id="chat_header_id">
            <div class="form-group">
                <input type="submit" name="submit" value="Update" class="btn btn-primary">            
            </div>
    
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                
            <?php
            
            if(!empty($group_access))
            {
            echo "
                <lable><b>Groups:</b></label>
                <table class='table table-striped table-bordered' id='mytable'>
                <tr>
                    <th>Group Name</th>
                    <th>View</th>
                    <th>Delete</th>                    
                </tr>
            ";
            $other_groups = array();
            foreach ($group_access as $value) {
                # code...
                $group_id = (int)$value['group_id'];
                $other_groups[] = $group_id; 
                $group_name = "Custom Members";
                if($group_id != 0)
                $group_name = get_groupname($group_id);
                echo "<tr>
                        <td>$group_name</td>

                        <td><button id=$group_id class='show_table'>View Members</button></td>                  
                        <td><a href='DeleteGroupAccess.php?group_id=$group_id'>Delete</a></td>                 
                    </tr>";
                
            }

            echo "</table>";
            echo "<button id='add_group_update'>Add Group</button>";

            }
            ?>
            </div>
            <div id="group_members">
                <?php

                foreach ($group_access as $group_access_value) 
                {
                    $group_id = (int)$group_access_value['group_id'];
                    $group_name = "Custom Group";
                    if($group_id != 0)
                    $group_name = get_groupname($group_id);
                    
                    echo '<div id=show'.$group_id.' class="grp">';
                    echo $group_name;
                    echo "
                    <table class='table table-striped table-bordered' id='mytable'>
                    <tr>
                        <th>User Name</th>
                        <th>Group Permission</th>
                        <th>Overriden Permission</th>             
                    </tr>
                    ";
                    $group_members=get_group_users($chat_header_id,$group_id);

                    foreach ($group_members as $key => $record) 
                    {
                        $user_id = $record['user_id'];
                        $permission = $record['access_right'];
                        $override_flag = (int)$record['override_flag'];
                        $name = get_name_byid($user_id);
                        if ($permission == 0) 
                        {
                            echo "<tr>
                                <td>$name</td>

                                ";
                                if($override_flag==0)
                            echo "
                                    <td>Readonly</td>
                                    <td></td>
                                </tr>";

                            elseif ($override_flag==1)
                            echo "
                                    <td>Readonly</td>
                                    <td>Disabled</td>                 
                                </tr>";

                            elseif ($override_flag==2)
                            echo "
                                    <td>Readonly</td>
                                    <td>Readonly</td>                 
                                </tr>";

                            elseif ($override_flag==3)
                            echo "
                                    <td>Readonly</td>
                                    <td>Read & Write</td>                 
                                </tr>";
                            
                        }
                        else
                        {
                            echo "<tr>
                                <td>$name</td>

                                ";
                                if($override_flag==0)
                            echo "
                                    <td>Read & Write</td>
                                    <td></td>
                                </tr>";

                            elseif ($override_flag==1)
                            echo "
                                    <td>Read & Write</td>
                                    <td>Disabled</td>                 
                                </tr>";

                            elseif ($override_flag==2)
                            echo "
                                    <td>Read & Write</td>
                                    <td>Readonly</td>                 
                                </tr>";

                            elseif ($override_flag==3)
                            echo "
                                    <td>Read & Write</td>
                                    <td>Read & Write</td>                 
                                </tr>";
                        }
                    }
                
                echo "</table>
                      </div>
                ";
                
                }

                echo "<div id='show_groups' class='grp'>
                <table class='table table-striped table-bordered'>
                <tr>
                    <th>Group Name</th>
                    <th>Check</th>             
                </tr>
                <tr>
                <td>All Members</td>
                <td><input type='checkbox' name='all_users' value='all_users'></td>                                         
                </tr>
                ";
                $groups = get_other_groups($chat_creator);
                foreach ($groups as $group) {
                        # code...
                        $group_name = $group['group_name'];
                        $each_group_id = $group['group_id'];
                        if(!in_array($each_group_id, $other_groups))
                        echo "
                            <tr>
                            <td>$group_name</td>
                            <td><input type='checkbox' name='check_group[]' value='$each_group_id'></td>                  
                            </tr>";     
                }
                echo "</table>
                <button id='next'>Next</button>
                </div>";
                
                
                ?>
            </div>
            
        </div>
    </form>
    </div>
</div> 
<script type="text/javascript">

function get_group()
{



}

$(document).ready(
function()
{
    $('.grp').hide();

    $(".show_table").click(function(){
        
        $('.grp').hide();
        var id = $(this).attr('id');
        id = "#show"+id;
        $(id).show();
        return false;
    });



    $(".override_action").click(function(){

        var values = $("input[name='permission_override[]']:checked").map(function(){return $(this).val();}).get();
        if(values != '')
        {
            if (confirm("Do you want to process")) {
                var action_val = parseInt($(this).val());  
                var chat_header_id = $('#chat_header_id').val();

                window.location.href='PermissionOverride.php?action='+action_val+'&&permission_override='+values;
                return false;

            }

            else
            {
                return false;
            }
        }

        else
        {
            return false;
        }

    });

    $('#all_members').on("click","#checkAll",function(){

        $("#modify_table tr td:nth-child(1) input[type=checkbox]").not(this).prop('checked', this.checked);

    });

    $('#add_group_update').click(
        function()
        {
            $('.grp').hide();
            $('#show_groups').toggle();
            //window.location.href='AddGroupMessage.php';
            return false;
        }
    );

    $('#next').click(
        function()
        {
            var values = '';
            values = $("input[name='check_group[]']:checked").map(function(){return $(this).val();}).get();
             
            var all_users = $("input[name=all_users]:checked").val();
            if(all_users==undefined)
                all_users = '';
            if(values!='' || all_users!='')
            window.location.href='AddGroupMessage.php?all_users='+all_users+'&&check_group='+values;

            return false;
        }
    );

}
);
</script>   
</body>
</html>