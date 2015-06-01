<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title">Download Files</h4>
</div>
<div class='modal-body'>
    <ul class="list-group">
    <?php

        foreach ($files as $file_id => $file)
        {
            echo "<li class='list-group-item'>".$file['name']."</li>";
        }

    ?>
    </ul>
    
    <?php
    $this->load->helper(array('form'));
    $this->load->library('encrypt');
	echo form_open_multipart("samples/download_files/".base64_encode($this->encrypt->encode($id)), array()); 
	
	?>
    <button id="submitBtn" type="submit" class="btn btn-primary">Download</button>
	<?php echo form_close(); ?>
</div>
<?php exit; ?>
