<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Sample_model extends CI_Model
{

	public function fetch_all()
	{
		$this->db->select('samples.id, files.id as fid, files.ext, samples.code, samples.recieved_time, samples.runstart_time, samples.runend_time, samples.solved_time, samples.invalid_time, samples.est_time, samples.status, samples.created, users.fname, users.lname, users.student_no, files.name');
		$this->db->from('samples');
		$this->db->join('files', 'samples.id = files.sample_id', 'LEFT');
		$this->db->join('users', 'samples.user_id = users.id', 'LEFT');
		$this->db->order_by("samples.id", "desc");
		$this->db->order_by("files.ext");
		$this->db->order_by("files.name");
		$query = $this->db->get();

		
		$results['headers'] = array('CIFs', 'Code', 'Status', 'Student #', 'User', '', '', '');

		if($query->num_rows() > 0)
		{
		    $results['rows'] = array(); 
		    $lastid = 0;
			foreach ($query->result() as $obj) 
			{
			    $id = $obj->id;
			    $results['rows'][$id]['code'] = $obj->code;
			    
			    if ($lastid != $id) 
			    {
			        $lines = array();
			        $results['rows'][$id]["status"] = "";
		        }
			    if ($obj->name != null)
			    {
			        $results['files'][$id][$obj->fid]['name'] = $obj->name;
			        $results['files'][$id][$obj->fid]['ext'] = $obj->ext;
		        }

                $blank = '0000-00-00 00:00:00';
			    if ($obj->recieved_time != $blank && $lastid != $id) $lines[] = "Recieved: ".date("d M Y H:i", strtotime($obj->recieved_time));
			    if ($obj->runstart_time != $blank && $lastid != $id) $lines[] = "<br/>Run started: ".date("d M Y H:i", strtotime($obj->runstart_time))."<br/>Estimated time of completion: ".date("d M Y H:i", strtotime("+".$obj->est_time." Minutes", strtotime($obj->runstart_time)));
			    if ($obj->runend_time != $blank && $lastid != $id) $lines[] = "<br/>Run completed (unsolved): ".date("d M Y H:i", strtotime($obj->runend_time));
			    if ($obj->invalid_time && $obj->invalid_time != $blank && $lastid != $id) $lines[] = "<br/>Invalid: ".date("d M Y H:i", strtotime($obj->invalid_time));
			    if ($obj->solved_time != $blank && $lastid != $id) $lines[] = "<br/>Solved: ".date("d M Y H:i", strtotime($obj->solved_time));
                
                if ($lastid != $id)
                {
                    foreach($lines As $line)
                    {
                        $results['rows'][$id]["status"] .= $line;
                    }
                    $lastid = $id;
                }              
			    
			    $results['rows'][$id]['student_no'] = $obj->student_no;
			    $results['rows'][$id]["user"] = $obj->fname." ".$obj->lname;
			}
		}

		return $results;
	}
	
	public function fetch_mine()
	{
    	$user_id = $this->session->userdata["logged_in"]["userid"];
		$this->db->select('samples.id, files.id as fid, files.ext, samples.code, samples.recieved_time, samples.runstart_time, samples.runend_time, samples.solved_time, samples.invalid_time, samples.est_time, files.name, status, created');
		$this->db->from('samples');
		$this->db->join('files', 'samples.id = files.sample_id', 'LEFT');
		$this->db->where('user_id', $user_id);
		$this->db->order_by("samples.id", "desc");
		$this->db->order_by("files.ext");
		$this->db->order_by("files.name");
		$query = $this->db->get();
		
		$results['headers'] = array('CIFs', 'Code', 'Status', '');
		
		
		if($query->num_rows() > 0)
		{
    		$lastid = 0;
		    $results['rows'] = array(); 
			foreach ($query->result() as $obj) 
			{
			    $id = $obj->id;
			    $results['rows'][$id]['code'] = $obj->code;
			    if ($lastid != $id) 
			    {
			        $lines = array();
			        $results['rows'][$id]["status"] = "";
		        }
		        
			    if ($obj->name != null)
			    {
			        $results['files'][$id][$obj->fid]['name'] = $obj->name;
			        $results['files'][$id][$obj->fid]['ext'] = $obj->ext;
		        }
		        
		        $blank = '0000-00-00 00:00:00';
			    if ($obj->recieved_time != $blank && $lastid != $id) $lines[] = "Recieved: ".date("d M Y H:i", strtotime($obj->recieved_time));
			    if ($obj->runstart_time != $blank && $lastid != $id) $lines[] = "<br/>Run started: ".date("d M Y H:i", strtotime($obj->runstart_time))."<br/>Estimated time of completion: ".date("d M Y H:i", strtotime("+".$obj->est_time." Minutes", strtotime($obj->runstart_time)));
			    if ($obj->runend_time != $blank && $lastid != $id) $lines[] = "<br/>Run completed (unsolved): ".date("d M Y H:i", strtotime($obj->runend_time));
			    if ($obj->invalid_time && $obj->invalid_time != $blank && $lastid != $id) $lines[] = "<br/>Invalid: ".date("d M Y H:i", strtotime($obj->invalid_time));
			    if ($obj->solved_time != $blank && $lastid != $id) $lines[] = "<br/>Solved: ".date("d M Y H:i", strtotime($obj->solved_time));
                
                if ($lastid != $id)
                {
                    foreach($lines As $line)
                    {
                        $results['rows'][$id]["status"] .= $line;
                    }
                    $lastid = $id;
                }
			}
		}

		return $results;
	}
	
	public function fetch($id)
	{
	    $query = $this->db->select("*")->from('samples')->where('id', $id)->get();
	    
	    if ($query->num_rows() > 0)
	    {
	        $sample = $query->result();    
	        return (array)$sample[0];
	    }
	    return 0;
	}
	
	public function update($post)
	{  	
	    if (!isset($post['id']) || $post['id'] == "") {
	        if (isset($post['id'])) unset($post['id']);
	        
	        $this->db->insert('samples', $post);
	        return $this->db->insert_id();
        }
        else {
            $id = $post['id'];
        	unset($post['id']);

            $this->db->where("id", $id);
    		$this->db->update('samples', $post);
    		if ($this->db->affected_rows() == 0)
    		{
    		    return -1;
    		}

    		return $id;
        }

        return 0;
	}
	
	public function delete($id)
	{
	    $this->load->model('File_model','',TRUE);
	    $files = $this->File_model->fetch_by_sample($id);
	    
	    if ($files)
	    {
	        foreach($files['files'] as $fid => $file)
	        {
	            unlink($file["path"]);
	            $this->db->delete('files', array('id' => $fid));
            }
	    }
	    $this->db->delete('samples', array('id' => $id));
	}
	
}


?>
