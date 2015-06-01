<?php 

$this->load->helper(array('form')); 
$this->load->library('encrypt');

?>

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title"><?php echo (isset($notice['id'])) ? "Edit Notice" : "New Notice"; ?></h4>
</div>
<div class="modal-body">
	<?php
    $attributes = array('id' => 'myform', 'role' => 'form', 'data-toggle' => 'validator');
	echo form_open_multipart("notices/update/", $attributes); 
	?>

	<input type='hidden' name='id' value="<?php if(isset($notice)) echo base64_encode($this->encrypt->encode($notice['id'])); ?>"/>
		
    <span class="form-group">
        <label for="title">Title</label>
        <input class="form-control" size="30" type="text" id="title" name="title" value="<?php if (isset($notice)) echo $notice['title']; ?>" required>
        </input>
        <span class="help-block with-errors"></span>
    </span>
	<span class="form-group">
		<label for="note">Notice</label>
		<input type="text" class="col-lg-12 form-control" id="note" name="note" value="<?php if (isset($notice)) echo $notice['note']; ?>" required></input>

		<span class="help-block with-errors"></span>
	</span>
	
	<div class='col-lg-12'>&nbsp;</div>
	<div class='col-lg-12'>&nbsp;</div>

        <div class="form-group">
	    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
	    <button id="submitBtn" type="submit" class="btn btn-primary">Save</button>
    	</div>
	<?php echo form_close();?>
</div>

