<?php

                foreach ($group_access as $value) 
                {
                $group_id = (int)$value['group_id'];
                $group_name = "All Members";
                if($group_id != 0)
                $group_name = get_groupname($group_id);
                echo $group_name;
                echo '<div id=show'.$group_id.'>';
                echo "
                <table class='table table-striped table-bordered' id='mytable'>
                <tr>
                    <th>User Name</th>
                    <th>Read</th>
                    <th>Write</th>             
                </tr>
                ";
                $group_members=get_group_users($chat_header_id,$group_id);

                foreach ($group_members as $key => $record) 
                {
                    $user_id = $record['user_id'];
                    $permission = $record['access_right'];
                    //$group_id = $record['group_id'];
                    $name = get_name_byid($user_id);
                    if ($permission == 0) 
                    {
                                        # code...
                        echo "<tr>
                            <td>$name</td>

                            <td><input type='checkbox' name='perm_read[$group_id][]' value='$user_id' checked readonly></td>                  
                            <td><input type='checkbox' name='perm_write[$group_id][]' value='$user_id' readonly></td>                 
                        </tr>";
                    }
                    else
                    {
                        echo "<tr>
                            <td>$name</td>

                            <td><input type='checkbox' name='perm_read[$group_id][]' value='$user_id' readonly></td>                  
                            <td><input type='checkbox' name='perm_write[$group_id][]' value='$user_id' checked readonly></td>                 
                        </tr>";
                    }
                }
                echo "</table>";

                }
                ?>