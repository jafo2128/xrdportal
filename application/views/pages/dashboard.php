    <?php
    $admin = $this->session->userdata["logged_in"]["admin"];
    if (isset($title) && $admin)
        echo "<div class='panel'><div class='panel-heading'><h3 class='panel-title'>$title</h3></div>";
    ?>
    <div class='panel-body'>
        <?php if (isset($msg)) { echo "<div class='alert alert-info'>"; print_r($msg); echo "</div>"; } ?>
        <?php if (isset($error)) { echo "<div class='alert alert-danger'>"; print_r($error); echo "</div>"; } ?>
        <?php if (isset($warning)) { echo "<div class='alert alert-warning'>"; print_r($warning); echo "</div>"; } ?>
        <div id='modal-window-href' class='modal' >
            <div class="modal-dialog">
                <div class="modal-content">
                <?php
                if (isset($href)) 
                {
                    echo "
			         <script type='text/javascript'>
				         $('#modal-window-href').modal({
					          keyboard: false,
					          remote: '$href'
					        });
			         </script>
			         ";
		         }
                ?>
                </div>
	        </div>
        </div>
    
        <span id="datamodels_container" class="col-lg-12 col-md-12 col-sm-12 col-xs-12"></span>
        
        </span>
    <!-- panel-body --> 
    </div>
<!-- panel -->
</div>
    
    <script type="text/javascript">

    <?php
        if (isset($endpoint))
        {
            echo "var endpoint = '".base_url($endpoint)."';";
        }
        else
        {
            echo "var endpoint = '".base_url()."samples/view/';";
        }
    ?>

    
    jQuery.ajax({url: endpoint, async: false, success: function(data) {
        if (data == "failed") {
        }
        else {
            $("#datamodels_container").html(data); 
        }
    }});
    
    $('.modal').on('hidden.bs.modal', function() { $(this).removeData('bs.modal'); });
        
    var newwindow;
    function poptastic(url)
    {
	    newwindow=window.open(url,'name','height=500,width=500');
	    if (window.focus) {newwindow.focus()}
    }
    
    $(".change_dashboard").click(function() {
        var view = $(this).attr('view');
        <?php echo "var url = '".base_url()."samples/view/';"; ?>
        if (view == "manageusers") {
            var url = "<?php echo base_url('users/view'); ?>";
        }
        else if (view == "dashboard") {
            var url = "<?php echo base_url('samples/view'); ?>";
        }
        else if (view == "managenotices") {
            var url = "<?php echo base_url('notices/view'); ?>";
        }
            
            
        jQuery.ajax({url: url, async: false, success: function(data) {
            if (data == "failed") {
            }
            else {
                $("#datamodels_container").html(data); 
            }
        }});
    });
    
    </script>
