<div id="form-register">
	<div class="title">Forgot your password?</div>
	<p class="subtitle">Please enter your email below to have a password reset link sent to you. The link sent will expire in 2 hours.</p>
	<?php echo validation_errors('<br/><br/><p class="error">'); ?>
	<br/><br/>
	<?php echo form_open("login/send_reset_password_link"); ?>
		<div class="form-group">
			<p>
				<label for="email_address">Your Email:</label>
				<input class="form-control" type="text" id="email_address" name="email_address" value="<?php echo set_value('email_address'); ?>" />
			</p>
			<p>
				<input type="submit" class="btn btn-primary" value="Submit" />
			</p>
		</div>
	<?php echo form_close(); ?>
</div>
