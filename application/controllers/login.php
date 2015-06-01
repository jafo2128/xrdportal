<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('User_model','',TRUE);
		$this->load->model('Notice_model','',TRUE);
	}
	
	function check_database($username, $password)
 	{
		$result = $this->User_model->login($username, $password);
        
		if(!$result)
		{
			echo 'Invalid email or password.';
		}
		else
		{
			$sess_array = array();
			foreach($result as $row)
			{
				$sess_array = array(
					'userid' => $row->id,
					'fname' => $row->fname,
					'lname' => $row->lname,
					'email' => $row->email,
					'admin' => $row->admin
				);
				$this->session->set_userdata('logged_in', $sess_array);
				session_start();
			}
			
			echo "success";
		}
	}
		
	public function logout()
	{
		$this->session->unset_userdata('logged_in');
		@session_destroy();
		redirect('view/home', 'refresh');
	}

	public function is_session_expired()
	{
		if(!$this->session->userdata('logged_in'))
		{
			echo "0";
		}
		else
		{
			echo "1";
		}
	}
	
    public function get_recent_notices() 
	{
		$notices = $this->Notice_model->fetch_last_week();
		echo json_encode($notices);
	}

}

?>
