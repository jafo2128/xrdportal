<div id="form-register">
	<div class="title">Reset your password</div>
	<p class="subtitle">Please enter your new password below.</p>
	<?php echo validation_errors('<br/><br/><p class="error">'); ?>
	<br/><br/>
	<?php echo form_open("login/reset_password"); ?>
		<div class="form-group">
			<input type='hidden' name='token' value="<?php echo $resettoken; ?>"/>
			<p>
				<label for="password">Password:</label>
				<input class="form-control" type="password" id="password" name="password" value="<?php echo set_value('password'); ?>" />
			</p>
			<p>
				<label for="con_password">Confirm Password:</label>
				<input class="form-control" type="password" id="con_password" name="con_password" value="<?php echo set_value('con_password'); ?>" />
			</p>
			<p>
				<input type="submit" class="btn btn-primary" value="Submit" />
			</p>
		</div>
	<?php echo form_close(); ?>
</div>
