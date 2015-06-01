<table style='width:100%; '>
<tr><td>
	<div id="login_section">

			<?php $this->load->helper(array('form')); ?>
			<?php 
	    		$attributes = array('id' => 'myform', 'role' => 'form', 'data-toggle' => 'validator'); 
			?>
			<?php echo form_open_multipart('', $attributes); ?>
			<div class="form-group">
				<input class="form-control" id="usr" name="usr" type="email" placeholder="Email" data-error="A valid email is required." required>
				<span class="help-block with-errors"></span>
			</div>
			<div class="form-group">
				<input class="form-control" id="pwd" name="pwd" type="password" placeholder="Password" data-minlength="6" required data-error="&nbsp;">
				<span class="help-block with-errors"></span>
				<button id="signin" type="submit" class="btn btn-primary">Sign In</button>
				<br/>
			</div>
			<span class="help-block with-errors" id="ajax_error"></span>
				<a style="color: black;" href="<?php echo base_url('login/forgot_password'); ?>">Forgot your password?</a>
			<?php echo form_close(); ?>
	</div></td></tr>
	<tr><td>
	<div id="notice_section" >
	    <div id="notice_board">
	        <div>
		        <script type='text/javascript'>
	            var notices;
	            var url = "<?php echo base_url(); ?>";
	            jQuery.ajax({url: url+"login/get_recent_notices", async: false, success: function(data) {
			            notices = data;
		            }
                });
	            notices = $.parseJSON(notices);
	            for (var i=0; i<notices.length; i++) {
	                document.write("<span class='label label-primary pull-right'>"+notices[i].created+"</span><div class='jumbotron'><h2>"+notices[i].title+"</h2><p>"+notices[i].note+"</p></div>");
	            }
	            
	
                </script>
            </div>
	    </div>
    </div>
	</td></tr></table>


	
<script type="text/javascript" >

$(document).ready(function() {
	var url = "<?php echo base_url(); ?>";
	HomeUI.initialize(url);
	
	$("#myform").validator()
        .on("submit", function(e) {
	        if (e.isDefaultPrevented()) {
	        }
	        else {
		    var usr = $("#usr").val();

		    var pwd = $.md5($("#pwd").val());
		    var endpoint = "<?php echo base_url(); ?>login/check_database/"+usr+"/"+pwd;

		    jQuery.ajax({url: endpoint, async: false, success: function(data) {
		        if (data == "success") {
		            location.replace("<?php echo base_url(); ?>view/dashboard");
		        }
		        else {
		            $("#ajax_error").text(data).css("color", "#A94442"); 
		            e.preventDefault();
		        }
		    }});	

	        }
	    });
    });

</script>
