<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends MY_Controller 
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('User_model','',TRUE);
		$this->load->library('encrypt');
	}
	
	public function view()
	{
	    $admin = $this->session->userdata["logged_in"]["admin"];

	    if ($admin)
	    {
            $data = $this->User_model->get_users();
            echo $this->load->view('users/manageusers', $data, TRUE);
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
	
	public function user($eid = 0)
	{
	    $admin = $this->session->userdata["logged_in"]["admin"];
        
        
        if ($admin)
        {
            if ($eid)
	        {
	            $id = $this->encrypt->decode(base64_decode($eid));
	            $data['user'] = $this->User_model->get_user($id);
			    echo $this->load->view('users/user', $data, TRUE);
            }
            else
            {
                echo $this->load->view('users/user', array(), TRUE);
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
        
        $r = -1;
        if ($admin)
        {    
            $p = $_POST;
            $p['id'] = $this->encrypt->decode(base64_decode($p['id']));  
            
            if (!$p['change_password'])
            {
                unset($p['password']);
                unset($p['c_password']);
            }
            else
            {
                unset($p['c_password']);
                $p['password'] = hash('sha256', $p['changeto_password']);
                unset($p['changeto_password']);
            } 
            unset($p['change_password']);
            $r = $this->User_model->update_user($p);
            
            if ($r == 0)
                $dashboard["error"] = "Failed.";
            else if ($r == -1)
                $dashboard["msg"] = "Nothing changed.";
            else
                $dashboard["msg"] = "Saved.";

            $dashboard['endpoint'] = "users/view";
            $dashboard['title'] = "";
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
    
    public function change_password()
    {
        $data['id'] = $this->session->userdata["logged_in"]["userid"];
        echo $this->load->view('users/changepassword', $data, TRUE);
    }
    
    public function change_user_password()
    {
        $p = $_POST;
        if (!isset($p['id']) || !$p['id'])
        {
            exit;
        }
        $p['id'] = $this->encrypt->decode(base64_decode($p['id']));
        $user_id = $this->session->userdata["logged_in"]["userid"];
        
        $r = -1;
        if ($p['id'] == $user_id)
        {     
              
            if (!isset($p['change_password']))
            {
                $dashboard["msg"] = "Nothing changed.";
            }
            else
            {
                unset($p['c_password']);
                unset($p['change_password']);
                $p['password'] = hash('sha256', $p['changeto_password']);
                unset($p['changeto_password']);
                
                $r = $this->User_model->update_user($p);
                
                if ($r == 0)
                    $dashboard["error"] = "Failed.";
                else if ($r == -1)
                    $dashboard["msg"] = "Nothing changed.";
                else
                    $dashboard["msg"] = "Saved.";
            } 
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
    
    public function delete($eid)
    {
        $admin = $this->session->userdata["logged_in"]["admin"];
        
        if ($admin)
        {
            $id = $this->encrypt->decode(base64_decode($eid));
            $success = $this->User_model->delete($id);
            if ($success == 0)
                $dashboard['error'] = "User was not deleted.";
            else
                $dashboard["warning"] = "User deleted.";
                
            $dashboard['endpoint'] = "users/view";
            $dashboard['title'] = "";
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
