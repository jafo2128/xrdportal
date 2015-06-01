<?php

$this->load->helper(array('form')); 
$this->load->library('encrypt');
$admin = $this->session->userdata["logged_in"]["admin"];

if ($admin)
{
    echo "<a href='".base_url('samples/sample')."' data-toggle='modal' data-target='#modal-window-href'><button value='New Sample' type='submit' class='btn btn-primary pull-left'>New Sample</button></a>";
}

?>
<div class='col-lg-12'>&nbsp;</div>

<table class='table'>
<?php

if (!isset($rows))
{

    echo ($admin) ? "<div class='alert alert-info'><a class='label label-primary' data-toggle='modal' data-target='#modal-window-href' href='".base_url('samples/sample')."'>Add</a> a new sample</div>" : "<div class='alert alert-info'>You have no samples.</div>";
    exit;
}

foreach($headers As $header) 
{
    echo "<th>$header</th>";
}

foreach($rows As $sample_id => $namevalue)
{
    echo "<tr>";
    
    $hasfiles = false;
    echo "<td>";
    if (isset($files) && isset($files[$sample_id]))
    {        
        foreach ($files[$sample_id] as $file_id => $file)
        {
            if ($file['ext'] == ".cif")
            {
                if ($hasfiles) echo "<br/>";
                echo "<a class='label label-primary' href='javascript:poptastic(\"".base_url("samples/jmol_applet")."/".base64_encode($this->encrypt->encode($file_id))."\");'>".$file['name']."</a>";
            }  
            $hasfiles = true;
        } 
    }
    echo "</td>";
    foreach ($namevalue As $name => $value)
    {
        echo "<td>$value</td>";
    }
    
    $esample_id = base64_encode($this->encrypt->encode($sample_id));
    if ($hasfiles) echo "<td><a class='label label-primary' data-toggle='modal' data-target='#modal-window-href'  href='".base_url("samples/download_dialog")."/$esample_id'>Download</a></td>";
    else echo "<td>&nbsp;</td>";
    if ($admin)
    {
        echo "<td><a class='label label-primary' data-toggle='modal' data-target='#modal-window-href' href='".base_url("samples/sample")."/$esample_id'>Edit</a></td>";
        
        echo "<td><a class='label label-danger' id='$esample_id' data-toggle='modal' data-target='#confirm-delete' href=''>Delete</a></td>";   
    }
        
    echo "</tr>";
}

?>

</table>
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Delete
            </div>
            <div class="modal-body">
                Are you sure?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a id='submit' class="btn btn-danger btn-ok">Delete</a>
            </div>
        </div>
    </div>
</div>

<script type='text/javascript'>

$('#confirm-delete').on('show.bs.modal', function(e) {
    var id = $(e.relatedTarget).attr('id');
    var url = "<?php echo base_url(); ?>samples/delete/"+id;
    $(this).find('.btn-ok').attr('href', url);
    
});
    

</script>


