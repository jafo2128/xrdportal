<?php

class Pages extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
    }

	public function view($page = 'home')
	{
		if (!file_exists('application/views/pages/'.$page.'.php'))
		{
			show_404();
		}
		
		if ($page == 'dashboard') 
		{
			if(!$this->session->userdata('logged_in'))
			{
				redirect('view/home', 'refresh');
			}
			
			$this->load->view('templates/'.$page.'header');
		    $this->load->view('navigation/'.$page);
		    $this->load->view('pages/'.$page);
		    $this->load->view('templates/footer');
		}
		
		if ($page == 'home')
		{
    		if ($this->session->userdata('logged_in')) 
    		{
    		    redirect('view/dashboard', 'refresh');
    		}
    		
		    $this->load->view('templates/'.$page.'header');
		    $this->load->view('pages/'.$page);
		    $this->load->view('templates/footer');
		}
	}
	
}

?>
