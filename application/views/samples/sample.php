<?php 
$this->load->helper(array('form')); 
$this->load->library('encrypt');
?>

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<?php 
	if (isset($sample))
    {
        $title = "Edit Sample";    
        $user_selected = $sample['user_id'];
        $status_selected = $sample['status'];
    }
    else
    {
        $title = "New Sample";
        $user_selected = "";
        $status_selected = "";
        $user = "";
    }
    ?>
	<h4 class="modal-title"><?php echo $title; ?></h4>
</div>
<div class="modal-body">  
        <span class="btn btn-success fileinput-button">
            <i class="glyphicon glyphicon-plus"></i>
            <span>Add files...</span>
            <!-- The file input field used as target for the file upload widget -->
            <input id="fileupload" type="file" name="userfile[]" multiple>
        </span>
        <br><br/>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Files</h3>
            </div>
            <div class="panel-body">
            <div style='display: none;' class="alert alert-info" id="msg"></div>
                <?php
                    if (isset($files) && $files)
                    {
                        echo "<ul class='list-group'>";
                        foreach($files['files'] as $fid => $file)
                        {
                            $fid = base64_encode($this->encrypt->encode($fid));
                            echo "<li class='list-group-item'>".$file['name']."<a class='delete_file' file_id='$fid' href='#'><span id='' style='cursor: pointer;' class='glyphicon glyphicon-remove pull-right'></span></a></li>";
                        }
                        echo "</ul>";
                    }
                ?>
                <!-- The container for the uploaded files -->
                <ul id="files" class="files list-group">
                </ul>
                <br/>
                <!-- The global progress bar -->
                <div id="progress" class="progress">
                    <div class="progress-bar progress-bar-success"></div>
                </div>
            </div>
        </div>
        
	<?php
	$attributes = array('id' => 'myform');
	echo form_open_multipart("samples/update/", $attributes); 
	?>
	<input type='hidden' name='username' value="<?php echo $user; ?>"/>
	<input type='hidden' name='id' value="<?php if(isset($sample)) echo base64_encode($this->encrypt->encode($sample['id'])); ?>"/>
	<input type='hidden' name='filename' id='filename'/>
	<div class="form-group">
        <label for="code">Sample Code</label>
        <input type="text" class="form-control" id="code" name="code" value="<?php if(isset($sample)) echo $sample['code']; ?>"/>
    </div>
    <div class="form-group">
        <label for="user">User</label>
        <select class="form-control" id="user" name="user">
        <?php 
            foreach($users As $id => $name)
            {
            
                $selected = "";
                if ($user_selected == $id)
                {
                    $selected = "selected";
                }
                echo "<option value='".base64_encode($this->encrypt->encode($id))."' $selected>$name</option>";
            }
        ?>
        </select>
    </div>
    <div class="form-group">
        <label for="status">Status</label>
        <select class="form-control" id="status" name="status">	
        <?php 
        $opentime = date('Y-m-d H:i');
            foreach($statuses As $name => $timestamp)
            {
            
                $selected = "";
                if ($status_selected == $name)
                {
                    $selected = "selected";
                    $opentime = $timestamp;
                }
                echo "<option $selected>$name</option>";
            }
        ?>
        </select>
    </div>
    <div class="form-group">
        <label for="dtp_input1" class="control-label">Status Timestamp</label>
        <div class="input-group date form_datetime " data-date="<?php echo $opentime; ?>" data-date-format="yyyy-mm-dd hh:ii" data-link-field="dtp_input1">
            <input class="form-control" size="16" type="text" id="datetime" name="datetime" value="<?php echo $opentime; ?>" readonly>
            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
			<span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
        </div>
		<input type="hidden" id="dtp_input1" value="" /><br/>
    </div>
    
    <div class="form-group">
        <label id="lbl_est_time" for="est_time"></label>
        <input style="display: none" name="est_time" id="est_time" class="form-control" placeholder="Minutes" />
    </div>
    
	<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
	<input id="submit" type="submit" value="Save" class="btn btn-primary"/>
	<?php echo form_close();?>
<br/><br/><br/><br/><br/><br/>
</div>


 
<script type="text/javascript">
    
	function read_file(input) {
	    if (input.files && input.files[0]) {
	        $("#uploaded").attr('src', "<?php echo base_url().'images/icons/cif_file.gif' ?>");
	    }
	}
	
	$("input[name='userfile']").change(function(){
	    read_file(this);
	});
	
	$(".form_datetime").datetimepicker({
        format: "yyyy-mm-dd hh:ii",
        autoclose: 1,
        startView: 2,
        weekStart: 1,
        todayBtn: 1,
        minuteStep: 15,
        forceParse: 0,
        showMeridian: 0,
        todayHighlight: 1
    });
    
    $('.delete_file').click(function(){
        var fileid = $(this).attr('file_id');
        var url = "<?php echo base_url(); ?>samples/delete_file/"+fileid;
        var a = $(this);
        jQuery.ajax({url: url, async: false, success: function(data) {
            if (data == "Failed") {
                $("#error").html(data);
            }
            else {
                $("#msg").css('display', '');
                $("#msg").html(data); 
                a.parent().css('display', 'none')
            }
        }});
    });

/*jslint unparam: true, regexp: true */
/*global window, $ */
$(function () {
    'use strict';
    <?php
        echo (isset($sample)) ? "var endpoint = '".base_url("samples/upload")."/".base64_encode($this->encrypt->encode($sample['id']))."';\n" : "var endpoint = '".base_url("samples/upload")."/0';\n";
        echo (isset($sample)) ? "var status = '".$sample['status']."';\n" : "var status = '';\n";
        if (isset($sample) and $sample['status'] == "Running" and $sample['est_time'])
        {
            echo "var esttime = '".$sample['est_time']."';\n";
        }
        if (isset($statuses))
        {
            echo "var statii = {\n\n\t";
            $cnt = 0;
            foreach($statuses as $name => $timestamp)
            {
                if ($cnt == 0)
                    echo "'$name':'$timestamp'";
                else
                    echo ",'$name':'$timestamp'";
                $cnt++;
            }
            echo "\n};";
        }
    ?>

    var est_time = $("#est_time").val();
    if (status == "Running") {
        $("#lbl_est_time").text("Estimated time to completion");
        $("#est_time").css("display", "visible");
        
        if (typeof esttime != "undefined" && esttime != null) $("#est_time").val(esttime);
    }
    $("#status").change(function () {
        var status = $("#status").val();
        if (status === "Running") {
            $("#lbl_est_time").text("Estimated time to completion");
            $("#est_time").css("display", "visible");
        }
        else {
            $("#lbl_est_time").text("");
            $("#est_time").css("display", "none");
        }
        var timestamp = statii[status];
        if (timestamp == "0000-00-00 00:00:00") {
            $("#dtp_input1").val("<?php echo date('Y-m-d G:i:s'); ?>");
            $("#datetime").val("<?php echo date('Y-m-d G:i:s'); ?>");
        }
        else {
            $("#dtp_input1").val(timestamp);
            $("#datetime").val(timestamp);
        }
    });
    // Change this to the location of your server-side upload handler:
    var url = endpoint,
        uploadButton = $('<span class="label label-primary"/>')
            .addClass('pull-right')
            .prop('disabled', true)
            .text('Processing')
            .css('cursor', 'pointer')
            .on('click', function () {
                var $this = $(this),
                    data = $this.data();
                $this
                    .off('click')
                    .text('Abort')
                    .on('click', function () {
                        $this.remove();
                        data.abort();
                    });
                data.submit().always(function () {
                    $this.remove();
                });
                $('#progress .progress-bar').css(
                    'width',
                    '0%'
                );
            });
    $('#fileupload').fileupload({
        url: url,
        dataType: 'json',
        autoUpload: false,
        maxFileSize: 50000000 // 50 MB
    }).on('fileuploadadd', function (e, data) {
        $('#progress .progress-bar').css(
            'width',
            '0%'
        );
        data.context = $('<div/>').appendTo($('#files'));
        $.each(data.files, function (index, file) {
            var name = file.name;
            var node = $('<li class="list-group-item"/>').text(name);
            if (!index) {
                node
                    .append(uploadButton.clone(true).data(data));
            }
            node.appendTo(data.context);
        });
    }).on('fileuploadprocessalways', function (e, data) {
        var index = data.index,
            file = data.files[index],
            node = $(data.context.children()[index]);
        if (file.preview) {
            node
                .prepend('<br>')
                .prepend(file.preview);
        }
        if (file.error) {
            node
                .append('<br>')
                .append($('<span class="text-danger"/>').text(file.error));
        }
        if (index + 1 === data.files.length) {
            data.context.find('span')
                .text('Upload')
                .prop('disabled', !!data.files.error);
        }
    }).on('fileuploadprogressall', function (e, data) {
        var progress = parseInt(data.loaded / data.total * 100, 10);
        $('#progress .progress-bar').css(
            'width',
            progress + '%'
        );
    }).on('fileuploaddone', function (e, data) {
        $.each(data.result.files, function (index, file) {
            if (file.url) {

                var link = $('<span class="glyphicon glyphicon-remove pull-right"/>')
                    .prop('href', file.url)
                    .on('click', function () {
                        
                    })
                    .css('cursor', 'pointer');

                $(data.context.children()[index])
                .append(link);
                
                $('#filename').val(file.name);
            } else if (file.error) {
                var error = $('<span class="text-danger"/>').text(file.error);
                $(data.context.children()[index])
                    .append('<br>')
                    .append(error);
            }
        });
    }).on('fileuploadfail', function (e, data) {
        $.each(data.files, function (index) {
            var error = $('<span class="text-danger"/>').text('File upload failed.');
            $(data.context.children()[index])
                .append('<br>')
                .append(error);
        });
    }).prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');
});
</script>
