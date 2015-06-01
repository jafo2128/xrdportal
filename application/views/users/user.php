<?php $this->load->helper(array('form')); ?>
<?php $this->load->library('encrypt'); ?>

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title" id='usermodal'>Edit User</h4>
</div>
<div class="modal-body">
	<?php
    $attributes = array('id' => 'myform', 'role' => 'form', 'data-toggle' => 'validator');
	echo form_open_multipart("users/update/", $attributes); 
	?>

	<input type='hidden' name='id' value="<?php if(isset($user)) echo base64_encode($this->encrypt->encode($user->id)); ?>"/>
		
	<span class="form-group col-xs-6">
		<label for="fname">First Name </label>
		<input class="form-control" type="text" id="fname" name="fname" value="<?php if (isset($user)) echo $user->fname; ?>" required>
		</input>
		<span class="help-block with-errors"></span>
	</span>
	<span class="form-group col-xs-6">
		<label for="lname">Last Name </label>
		<input class="form-control" type="text" id="lname" name="lname" value="<?php if (isset($user)) echo $user->lname; ?>" required>
		</input>
		<span class="help-block with-errors"></span>
	</span>
	<div class='col-lg-12'>&nbsp;</div>
	<span class="form-group col-xs-6">
		<label for="student_no">Student # </label>
		<input class="form-control" type="text" id="student_no" name="student_no" value="<?php if (isset($user)) echo $user->student_no; ?>" required>
		</input>
		<span class="help-block with-errors"></span>
	</span>
	<span class="form-group col-xs-6">
		<label for="email">Email </label>
		<input class="form-control" type="email" autocomplete="off" id="email" name="email" value="<?php if (isset($user)) echo $user->email; ?>" required>
		</input>
		<span class="help-block with-errors"></span>
	</span>
	
	<div class='col-lg-12'>&nbsp;</div>
	<span class="form-group col-xs-6">
		<label for="password"><?php if (isset($user)) echo "Change Password"; else echo "Password"; ?></label>
		<input class="form-control" data-minlength="6" autocomplete="off" type="password" id="password" name="password" value="<?php if (isset($user)) echo 'password'; ?>">
		</input>
		<span class="help-block with-errors"></span>
	</span>
	<span class="form-group col-xs-6">
		<label for="c_password">Confirm Password </label>
		<input class="form-control" data-minlength="6" type="password" id="c_password" name="c_password" data-match="#password" data-match-error="Whoops, these don't match" value="<?php if (isset($user)) echo 'password'; ?>">
		</input>
		<span class="help-block with-errors" id="password_error"></span>
	</span>
	<div class='col-lg-12'>&nbsp;</div>
	<div class='col-lg-12'>&nbsp;</div>

        <div class="form-group">
	    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
	    <button id="submitBtn" type="submit" class="btn btn-primary">Save</button>
    	</div>
	<?php echo form_close();?>
</div>
<script type='text/javascript'>
    $(document).ready(function() {
        
        $("#myform").validator().on("submit", function(e) {
		    if (e.isDefaultPrevented()) {
		    }
		    else {
	            //e.preventDefault();
			    var c_pwd = $.md5($("#c_password").val());
			    var pwd = $.md5($("#password").val());
			    
			    if (c_pwd != pwd) {
				    $("#password_error").text("Whoops, these don't match").css("color", "#A94442"); 
			    }
			    else {
				    if (pwd == $.md5("password") || pwd == $.md5("")) {
				        // Don't change the password
					    var input = $("<input>").attr("type", "hidden").attr("name", "change_password").val("0");
					    $("#myform").append($(input));
				    }
				    else { 
					    // Change password
					    var input = $("<input>").attr("type", "hidden").attr("name", "password").val("");
					    var input0 = $("<input>").attr("type", "hidden").attr("name", "c_password").val("");
					    var input1 = $("<input>").attr("type", "hidden").attr("name", "change_password").val("1");
					    var input2 = $("<input>").attr("type", "hidden").attr("name", "changeto_password").val(pwd);
					    $("#myform").append($(input));
					    $("#myform").append($(input0));
					    $("#myform").append($(input1));
					    $("#myform").append($(input2));
					    $("#password_error").text("");
				    }
			    }
		    }
        });

	$("#password").change(function() {
		$("#password_error").text("");
	});
        
        
    });
</script>

