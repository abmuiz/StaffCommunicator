
<?php



function get_name_byid($user_id)
{
	include('config.php');

	try
	{
		$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username,$password);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$stmnt = $conn->prepare("SELECT `name` FROM tbl_user WHERE user_id=:user_id"); 

		$stmnt->bindParam(':user_id',$user_id);

		$stmnt->execute();
		$records = $stmnt->fetchAll();
		$conn=NULL;
		foreach ($records as $value) {
			# code...
			$name = $value['name'];
		}
		return $name;
	}

	catch(PDOException $e)
	{
		echo "Error: " . $e->getMessage();
	}

}

function get_groupname($group_id)
{
	include('config.php');

	try
	{
	$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username,$password);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$stmnt = $conn->prepare("SELECT `group_name` FROM tbl_group WHERE group_id=:group_id"); 

	$stmnt->bindParam(':group_id',$group_id);

	$stmnt->execute();
	$records = $stmnt->fetchAll();
	$conn=NULL;
	foreach ($records as $value) {
		# code...
		$group_name = $value['group_name'];
	}
	return $group_name;
	}

	catch(PDOException $e)
	{
	echo "Error: " . $e->getMessage();
	}

}

function all_group_members($chat_header_id)
{
	include('config.php');

	try
	{
		$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username,$password);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$stmnt = $conn->prepare("SELECT `access_right`,`override_flag`,`user_id`,`group_id` FROM tbl_access WHERE chat_header_id=:chat_header_id"); 

		$stmnt->bindParam(':chat_header_id',$chat_header_id);

		$stmnt->execute();
		$records = $stmnt->fetchAll();
		$conn=NULL;
		return $records;
	}

	catch(PDOException $e)
	{
		echo "Error: " . $e->getMessage();
	}

}

function get_group_users($chat_header_id,$group_id)
{
include('config.php');

try
{
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username,$password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmnt = $conn->prepare("SELECT `access_right`,`override_flag`,`user_id` FROM tbl_access WHERE chat_header_id=:chat_header_id AND group_id=:group_id"); 

$stmnt->bindParam(':chat_header_id',$chat_header_id);
$stmnt->bindParam(':group_id',$group_id);

$stmnt->execute();
$records = $stmnt->fetchAll();
$conn=NULL;
return $records;
}

catch(PDOException $e)
{
echo "Error: " . $e->getMessage();
}

}

function get_chat_creator($chat_header_id)
{
	include('config.php');

	try
	{
	$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username,$password);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$stmnt = $conn->prepare("SELECT `chat_creator` FROM tbl_chat_header WHERE chat_header_id=:chat_header_id"); 

	$stmnt->bindParam(':chat_header_id',$chat_header_id);

	$stmnt->execute();
	$records = $stmnt->fetchAll();
	$conn=NULL;
	
	foreach ($records as $key => $value) {
		# code...
		$chat_creator = $value['chat_creator'];
	}
	return $chat_creator;
	}

	catch(PDOException $e)
	{
	echo "Error: " . $e->getMessage();
	}

}

function update_tbl_access($override_flag,$chat_header_id_condition,$user_id_condition)
        {
            include('config.php');
            $update_user_id=get_chat_creator($chat_header_id_condition);
            try
            {
            	if($user_id_condition!=$update_user_id)
            	{
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username,$password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $stmnt = $conn->prepare("UPDATE tbl_access SET `override_flag`=:override_flag WHERE `chat_header_id`=:chat_header_id_condition AND user_id=:user_id_condition"); 

                $stmnt->bindParam(':override_flag',$override_flag);
                $stmnt->bindParam(':chat_header_id_condition',$chat_header_id_condition);
                $stmnt->bindParam(':user_id_condition',$user_id_condition);

                $stmnt->execute();
                $conn=NULL;
            	}
            }

            catch(PDOException $e)
            {
                echo "Error: " . $e->getMessage();
            }

}

function get_other_groups($chat_creator)
{

	include 'config.php';

	$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username,$password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$stmnt = $conn->prepare("SELECT * FROM tbl_group WHERE group_creator=:chat_creator");
    $stmnt->bindParam(':chat_creator',$chat_creator);
    $stmnt->execute();
    $groups = $stmnt->fetchAll();

    return $groups;
}

function get_chat_header_details($chat_header_id)
{

		include 'config.php';
		$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username,$password);
    	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$stmnt = $conn->prepare("SELECT * FROM tbl_chat_header WHERE chat_header_id=:chat_header_id");
        $stmnt->bindParam(':chat_header_id',$chat_header_id); 
        $stmnt->execute();
        $records = $stmnt->fetchAll();

        return $records;
}

?>