<?php

echo '

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
                    
                    
                    <li class="dropdown active"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Messages <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="Messages.php">List Messages</a></li>
                            
                            <li><a href="ListMessages.php">My Messages</a></li>
                            

                        </ul>
                    </li>
                    <li class="dropdown "><a href="ListUsers.php" >Users</a>
                        <!--<ul class="dropdown-menu">
                            <li><a href="ListUsers.php">List Users</a></li>
                            
                        </ul>-->
                    </li>
                    <li class="dropdown "><a href="ListGroups.php" >Groups</a>
                        <!--<ul class="dropdown-menu">
                            <li><a href="ListGroups.php">List Groups</a></li>
                            
                        </ul>-->
                    </li>
                    <li><a href="Logout.php">Logout</a></li>
                    
                </ul>
            </div><!-- /.navbar-collapse -->
        </nav>

';

?>