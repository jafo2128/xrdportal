<?php 
$this->load->helper(array('form')); 
$this->load->library('encrypt');

$admin = $this->session->userdata["logged_in"]["admin"];


echo "<a href='".base_url('users/user')."' data-toggle='modal' data-target='#modal-window-href'>"; 
?>
<button value='New User' type="submit" class="btn btn-primary pull-left">New User</button>
</a>

<div class='col-lg-12'>&nbsp;</div>

<?php
if (!isset($rows))
{
    exit;
}
?>

<table class='table'>

<?php 

foreach($headers As $header) 
{
    echo "<th>$header</th>";
}

foreach($rows As $index => $namevalue)
{
    echo "<tr>";
    foreach ($namevalue As $name => $value)
    {
        if ($name == "id")
        {
            $id = $value;
        }
        else if ($name == 'studentno')
        {
            echo "<td><a class='label label-primary' data-toggle='modal' data-target='#modal-window-href' href='".base_url("users/user")."/".base64_encode($this->encrypt->encode($id))."'>$value</a></td>";
        }
        else 
        {
            echo "<td>$value</td>";
        }
    }
    echo "<td><a id='".base64_encode($this->encrypt->encode($id))."' class='label label-danger' href='' data-toggle='modal' data-target='#confirm-delete' >Delete</a></td>";
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
    var url = "<?php echo base_url(); ?>users/delete/"+id;
    $(this).find('.btn-ok').attr('href', url);
    
});


