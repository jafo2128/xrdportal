<!DOCTYPE html>
<html lang="en">
<head>

	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>UJ XRD Portal</title>
	

	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">

	<link href="<?php echo base_url('/css/dashboard/dashboard.css'); ?>" rel="stylesheet"/>
    
    <script type="text/javascript" src="<?php echo base_url('/js/validator.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('/js/jquery.md5.js'); ?>"></script>
	
	<script type="text/javascript" src="<?php echo base_url('/js/Jmol.js'); ?>"></script>
	

	
<link rel="stylesheet" href="<?php echo base_url('/css/jquery.fileupload.css'); ?>">

<script src="<?php echo base_url('/js/jquery.ui.widget.js'); ?>"></script>
<script src="<?php echo base_url('/js/jquery.fileupload.js'); ?>"></script>
<script src="<?php echo base_url('/js/jquery.fileupload-process.js'); ?>"></script>
<script src="<?php echo base_url('/js/jquery.fileupload-validate.js'); ?>"></script>

<script src="<?php echo base_url('/js/bootstrap-datetimepicker.min.js'); ?>"></script>
<link href="<?php echo base_url('/css/bootstrap-datetimepicker.min.css'); ?>" rel="stylesheet"/>

<script type='text/javascript'>

var ses_ep = "<?php echo base_url('login/is_session_expired'); ?>";
var mil_sec = 180*1000; //every 3 minutes

setInterval(function(){
    jQuery.ajax({url: ses_ep, async: false, success: function(data) {
            if (data == "0") {
                window.location = "<?php echo base_url('view/home'); ?>";
            }
        }});
}, mil_sec);
        
</script>

</head>
<body>	
