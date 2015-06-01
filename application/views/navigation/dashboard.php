<div class="navbar navbar-default" role="navigation" style=" z-index: 5;">
    <div class="container-fluid">
      <div class="navbar-header">
      <a class="navbar-brand pull-left change_dashboard" view="dashboard" style="font-family: 'Roboto', sans-serif;" href="#"><img style="height:30px;margin:0;padding:0;" src="<?php echo base_url('/images/logo.png'); ?>"/>&nbsp;&nbsp;&nbsp;XRD Portal</a>
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
      <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
        
      </div>
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav nav-pills">
          <li class="dropdown">
            <a href="" class="dropdown-toggle" data-toggle="dropdown">
              <span class="glyphicon glyphicon-user"></span>

              <ul class="dropdown-menu">
              <?php
              $admin = $this->session->userdata["logged_in"]["admin"];
              if ($admin)
              {
                echo "<li><a class='change_dashboard' view='manageusers' href='#'>Manage Users</a></li>";
              }
              ?>
                
                <li><a data-toggle='modal' data-target='#modal-window-href' href="<?php echo base_url('users/change_password'); ?>">Change Password</a></li>
                <li class="divider"></li>
                <li><a href="<?php echo base_url('login/logout'); ?>">Log Out</a></li>
              </ul>
            </a>
          </li>
          <?php
          if ($admin)
          {        
	        echo "<li class='dropdown'>";
            echo "<a href='' class='dropdown-toggle' data-toggle='dropdown'>";
              echo "<span class='glyphicon glyphicon-pushpin'></span>";
            
			    echo "<ul class='dropdown-menu' role='menu'>";
			        echo "<li><a class='change_dashboard' style='cursor: pointer' view='managenotices'>Manage Notices</a></li>";
			        echo "</ul></a></li>";
            
          }
          ?>
        </ul>
              <p class="navbar-text navbar-right">Signed in as <span class="label label-warning">
              <?php 
                $session_data = $this->session->userdata('logged_in');
                echo $session_data['fname']." ".$session_data['lname'];
              ?>
              </span></p>
      
      </div>
    </div>
    
</div>

