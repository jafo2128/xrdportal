<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notices extends MY_Controller 
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('Notice_model','',TRUE);
		$this->load->library('encrypt');
	}
	
	public function view()
	{
	    $admin = $this->session->userdata["logged_in"]["admin"];

	    if ($admin)
	    {
            $data = $this->Notice_model->fetch_all();
            echo $this->load->view('notices/managenotices', $data, TRUE);
        }
        else
        {
            $data['error'] = "Permission denied. Please contact administrator";    
            
            $this->load->view('templates/dashboardheader');
        	$this->load->view('navigation/dashboard');
        	$this->load->view('pages/dashboard', $data);
        	$this->load->view('templates/footer');  
        }
	}
	
	public function notice($e_id = 0)
	{
	    $admin = $this->session->userdata["logged_in"]["admin"];

	    if ($admin)
	    {
	        if ($e_id)
	        {
	            $id = $this->encrypt->decode(base64_decode($e_id));            
	            $data['notice'] = $this->Notice_model->fetch($id);
	            echo $this->load->view('notices/notice', $data, TRUE);
            }
            else
            {
                echo $this->load->view('notices/notice', array(), TRUE);
            }
        }
        else
        {
            $dashboard['error'] = "Permission denied. Please contact administrator"; 
            
            $this->load->view('templates/dashboardheader');
        	$this->load->view('navigation/dashboard');
        	$this->load->view('pages/dashboard', $dashboard);
        	$this->load->view('templates/footer');   
        }
	}
	
	public function update()
	{
	    $admin = $this->session->userdata["logged_in"]["admin"];
        
        $r = 0;
        if ($admin)
        {
            
            $p = $_POST;
            $p['id'] = $this->encrypt->decode(base64_decode($p['id']));
            $r = $this->Notice_model->update($p);
            $dashboard['endpoint'] = "notices/view";
            $dashboard['title'] = "";
            
            if ($r == 0)
                $dashboard["error"] = "Failed.";
            else if ($r == -1)
                $dashboard["msg"] = "Nothing changed.";
            else
                $dashboard["msg"] = "Saved.";
        }
        else
        {
            $dashboard['error'] = "Permission denied. Please contact administrator";
        }

        $this->load->view('templates/dashboardheader');
    	$this->load->view('navigation/dashboard');
    	$this->load->view('pages/dashboard', $dashboard);
    	$this->load->view('templates/footer');
	}
	
	public function delete($e_id)
    {
        $admin = $this->session->userdata["logged_in"]["admin"];
        
        if ($admin)
        {
            $id = $this->encrypt->decode(base64_decode($e_id));
            $this->Notice_model->delete($id);
            $dashboard['warning'] = "Notice deleted.";           
            $dashboard['endpoint'] = "notices/view";
        }
        else
        {
            $dashboard['error'] = "Permission denied. Please contact administrator";
        }

        $this->load->view('templates/dashboardheader');
    	$this->load->view('navigation/dashboard');
    	$this->load->view('pages/dashboard', $dashboard);
    	$this->load->view('templates/footer');
    }
}

?>
