<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Samples extends MY_Controller 
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('Sample_model','',TRUE);
		$this->load->model('User_model','',TRUE);
		$this->load->model('File_model','',TRUE);
		$this->load->model('Download_model','',TRUE);
		
		$this->load->library('encrypt');
	}
	
	public function view()
	{
	    $admin = $this->session->userdata["logged_in"]["admin"];

	    if ($admin)
	    {
	        $result = $this->Sample_model->fetch_all();
			echo $this->load->view('samples/samples', $result, TRUE);
        }
        else
        {
            $result = $this->Sample_model->fetch_mine();
			echo $this->load->view('samples/samples', $result, TRUE);    
        }
        
	}
	
	public function sample($eid = 0, $data = array())
	{
    	$admin = $this->session->userdata["logged_in"]["admin"];

        if ($admin) 
	    {
            if ($eid)
            {
                $id = $this->encrypt->decode(base64_decode($eid));
	            $sample = $this->Sample_model->fetch($id);
	            $data['statuses'] = array(
	                'Recieved' => $sample['recieved_time'],
	                'Running' => $sample['runstart_time'],
	                'Run complete (unsolved)' => $sample['runend_time'],
	                'Solved' => $sample['solved_time'],
	                'Invalid' => $sample['invalid_time']
                );
            }
            else
            {
                $data['statuses'] =  array(
	            'Recieved' => '0000-00-00 00:00:00',
	            'Running' => '0000-00-00 00:00:00',
	            'Run complete (unsolved)' => '0000-00-00 00:00:00',
	            'Solved' => '0000-00-00 00:00:00',
	            'Invalid' => '0000-00-00 00:00:00');
            }
	        
	        $data['users'] = $this->User_model->get_user_list();
	        
	        
	        $user_id = $this->session->userdata["logged_in"]["userid"];
	        
            if (isset($sample) && $sample)
            {
                $data['sample'] = $sample;
                $data['user'] = ($user_id == $sample['user_id']) ? null : $data['users'][$sample['user_id']];
                $data['files'] = $this->File_model->fetch_by_sample($this->encrypt->decode(base64_decode($eid)));
            }
            
		    $this->load->view('samples/sample', $data);
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
    	
    	if ($admin)
    	{
	        $post["user_id"] = $this->encrypt->decode(base64_decode($_POST["user"]));
            $post["status"] = $_POST["status"];
            $post["id"] = $this->encrypt->decode(base64_decode($_POST["id"]));
            if (isset($_POST["code"]) && $_POST["code"]) $post["code"] = $_POST["code"];
            if (isset($_POST['est_time']) && $_POST['est_time']) $post['est_time'] = $_POST['est_time'];
            
            
            if (!$_POST['id'] && isset($_POST['filename']))
            {
                $files = $this->File_model->fetch_by_filename($_POST['filename']);
                
                if (!$files) {
                }
                else
                {
                    $files = $files['files'];
                    foreach($files as $id => $file)
                    {
                        $post['id'] = $file['sample_id'];
                    }               
                }
            }
            
            if (isset($post['status']))
            {
                if (isset($_POST['datetime']) && $_POST['datetime'])
                {
                    if ($post['status'] == "Recieved")
                        $post['recieved_time'] = $_POST['datetime'];
                    if ($post['status'] == "Running")
                        $post['runstart_time'] = $_POST['datetime'];
                    if ($post['status'] == "Run complete (unsolved)")
                        $post['runend_time'] = $_POST['datetime'];
                    if ($post['status'] == "Solved")
                        $post['solved_time'] = $_POST['datetime'];
                    if ($post['status'] == "Invalid")
                        $post['invalid_time'] = $_POST['datetime'];
                }
            //"-", "Recieved", "Running", "Run complete (unsolved)", "Invalid sample", "Solved");
                
            }

            if (!isset($data['error']))
            {
                $success = $this->Sample_model->update($post);

                if ($success > 0)
                {
                    $data['msg'] = "Saved.";
                }
                else if ($success == -1)
                {
                    $data["msg"] = "Nothing changed.";
                }
                else
                {
                    $data['error'] = "Failed.";
                }     
            }        		    

	        $this->load->view('templates/dashboardheader');
            $this->load->view('navigation/dashboard');
            $this->load->view('pages/dashboard', $data);
            $this->load->view('templates/footer');     
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
	
	public function upload($eid)
	{
	    $admin = $this->session->userdata["logged_in"]["admin"];
	    
	    if ($admin)
	    {
        	$files = $_FILES;
        	$this->fix_files_array($files['userfile']);
	        $this->load->library('upload');
	        $id = $this->encrypt->decode(base64_decode($eid));
	        
	        foreach ($files['userfile'] as $index => $file)
            {
                if ($file['name'] != '')
                {
                    $_FILES['userfile'] = $file;
                    $config['upload_path'] = './images/uploads';
                    $config['allowed_types'] = "*";
                    $config['max_size'] = '30000';
                    $this->upload->initialize($config);

                    if (!$this->upload->do_upload())
                    {
                        $data['error'] = $this->upload->display_errors();
                    }
                    else
                    {
                        $data = $this->upload->data();   
                        
                        
                        $this->File_model->add($data['full_path'], $data['file_name'], $data['file_ext'], isset($id) ? $id : 0);
                    
                        //set the data for the json array
                        $info = new StdClass;
                        $info->name = $data['file_name'];
                        $info->size = $data['file_size'] * 1024;
                        $info->type = $data['file_type'];
                        $info->url = base_url("images/uploads")."/".$data['file_name'];
                        // I set this to original file since I did not create thumbs.  change to thumbnail directory if you do = $upload_path_url .'/thumbs' .$data['file_name']
                        $info->thumbnailUrl = base_url("images/uploads")."/".$data['file_name'];
                        $info->deleteUrl = base_url() . 'upload/deleteImage/' . $data['file_name'];
                        $info->deleteType = 'DELETE';
                        $info->error = null;   
                        
                        $files[] = $info;
                        //this is why we put this in the constants to pass only json data
                        if (IS_AJAX) {
                            echo json_encode(array("files" => $files));
                            //this has to be the only data returned or you will get an error.
                            //if you don't give this a json array it will give you a Empty file upload result error
                            //it you set this without the if(IS_AJAX)...else... you get ERROR:TRUE (my experience anyway)
                            // so that this will still work if javascript is not enabled
                        } else {
                            $file_data['upload_data'] = $this->upload->data();
                            $this->load->view('upload/upload_success', $file_data);
                        }              
                    }
                }
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
	
	public function jmol_applet($eid)
	{
	    $admin = $this->session->userdata["logged_in"]["admin"];
        $user_id = $this->session->userdata["logged_in"]["userid"];
        
        if (!$eid) 
        {
            echo "0";
            return;
        }
        
        $id = $this->encrypt->decode(base64_decode($eid));
        $data['file'] = $this->File_model->fetch($id);	
        $s = $this->Sample_model->fetch($data['file']['sample_id']);
        
        if ($user_id != $s['user_id'] && !$admin)
        {
            $dashboard['error'] = "Permission denied. Please contact administrator"; 
        
            $this->load->view('templates/dashboardheader');
        	$this->load->view('navigation/dashboard');
        	$this->load->view('pages/dashboard', $dashboard);
        	$this->load->view('templates/footer');
        }
        else
        {
            echo $this->load->view('samples/jmol_window', $data, TRUE);
        }
	}
	
	public function download_dialog($eid)
	{
	    $id = $this->encrypt->decode(base64_decode($eid));
	    $admin = $this->session->userdata["logged_in"]["admin"];
        $user_id = $this->session->userdata["logged_in"]["userid"];
        $sample = $this->Sample_model->fetch($id);
        
        if ($user_id != $sample['user_id'] && !$admin)
        {
            $dashboard['error'] = "Permission denied. Please contact administrator"; 
            
            $this->load->view('templates/dashboardheader');
        	$this->load->view('navigation/dashboard');
        	$this->load->view('pages/dashboard', $dashboard);
        	$this->load->view('templates/footer');   
        }
        else
        {
	        $data = $this->File_model->fetch_by_sample($id);
	        $data['id'] = $id;	    
	        $this->load->view('samples/downloadfiles', $data, TRUE);
        }
	}
	
	public function download_files($eid)
	{
	    $admin = $this->session->userdata["logged_in"]["admin"];
	    $user_id = $this->session->userdata["logged_in"]["userid"];
	    
    	$this->load->library('zip');
    	$id = $this->encrypt->decode(base64_decode($eid));
    	$sample = $this->Sample_model->fetch($id);
	    $files = $this->File_model->fetch_by_sample($id);
	    
	    if ($user_id != $sample['user_id'] && !$admin)
	    {
	        $dashboard['error'] = "Permission denied. Please contact administrator"; 
            
            $this->load->view('templates/dashboardheader');
        	$this->load->view('navigation/dashboard');
        	$this->load->view('pages/dashboard', $dashboard);
        	$this->load->view('templates/footer'); 
	    }
	    else
	    {
	        foreach($files['files'] as $fid => $file)
	        {
	            $this->zip->read_file($file['path'], false);
	        }
	        
	        $zipname = $sample['code']."_".strtotime('now').".zip";
	        $this->Download_model->add($zipname, $id);

            $this->zip->download($zipname);
	    }
	    
	     
	}
	
	public function delete($eid)
	{
    	$admin = $this->session->userdata["logged_in"]["admin"];
    	
    	if ($admin)
    	{
    	    $this->Sample_model->delete($this->encrypt->decode(base64_decode($eid)));
	    
	        $data["warning"] = "Sample deleted.";
	        
	        $this->load->view('templates/dashboardheader');
            $this->load->view('navigation/dashboard');
            $this->load->view('pages/dashboard', $data);
            $this->load->view('templates/footer');
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
	
	public function delete_file($eid)
	{
	    $admin = $this->session->userdata["logged_in"]["admin"];
    	
    	if ($admin)
    	{
	        $filename = $this->File_model->delete($this->encrypt->decode(base64_decode($eid)));
	        echo "$filename deleted";
        }
    	else
    	{
    	    $dashboard['error'] = "Permission denied. Please contact administrator."; 
            
            $this->load->view('templates/dashboardheader');
        	$this->load->view('navigation/dashboard');
        	$this->load->view('pages/dashboard', $dashboard);
        	$this->load->view('templates/footer');
    	}
	}
	
	public function fix_files_array(&$files)
	{
	    $names = array('name' => 1, 'type' => 1, 'tmp_name' => 1, 'error' => 1, 'size' => 1);
	    
	    foreach($files as $key => $part)
	    {
	        $key = (string) $key;
	        if (isset($names[$key]) && is_array($part))
	        {
	            foreach ($part as $position => $value)
	            {
	                $files[$position][$key] = $value;
	            }
	            unset($files[$key]);
	        }
	    }
	}
	
}


?>
