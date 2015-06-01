<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notice_model extends CI_Model
{
    
    public function fetch($id)
    {
        $query = $this->db->select('id, note, title')->from('notices')->where('id', $id)->get();
		
		$results = array();
		if($query->num_rows() > 0)
		{
			foreach ($query->result() as $obj) 
			{
			    $results['id'] = $obj->id;
			    $results['title'] = $obj->title;
    			$results['note'] = $obj->note;
			}
		}
		
		return $results;
    }
    
    public function fetch_last_week()
    {
        $one_week = date("Y-m-d G:i:s", strtotime('-1 Week', strtotime('now')));
        $query = $this->db->select('id, created, title, note')->from('notices')->where('created >=', $one_week)->order_by("id desc")->get();
		
		$results = array();
		if($query->num_rows() > 0)
		{
			$cnt = 0;
			foreach ($query->result() as $obj) 
			{
    			$results[$cnt]['id'] = $obj->id;
    			$results[$cnt]['note'] = $obj->note;
    			$results[$cnt]['title'] = $obj->title;
			    $results[$cnt]['created'] = date("d M Y G:i", strtotime($obj->created));
			    $cnt++;
			}
		}
		
		return $results;
    }
    
    public function fetch_all()
    {
        $query = $this->db->select('id, created, note, title')->from('notices')->order_by("id desc")->get();

		$results['headers'] = array('Created', 'Title', 'Notice', '', '');

		if($query->num_rows() > 0)
		{
		    $results['rows'] = array(); 
			foreach ($query->result() as $obj) 
			{

			    $r["created"] = $obj->created;
			    $r['title'] = $obj->title;
			    $r["note"] = $obj->note;
			    $r["id"] = $obj->id;
			     
				$results['rows'][] = $r;
			}
		}
		
		return $results;
    }
    
    public function delete($id)
    {
        $this->db->delete('notices', array('id' => $id));
    }
    
    public function update($post)
    {
        $this->load->library('encrypt');
        
        $admin = $this->session->userdata["logged_in"]["admin"];
        if ($admin)
        {
	        if (!isset($post['id']) || $post['id'] == 0 || !$post['id']) {
	            if (isset($post['id'])) unset($post['id']);
	            $this->db->insert('notices', $post);
	            return $this->db->insert_id();
            }
            else {
                $id = $post['id'];
            	unset($post['id']);

                $this->db->where("id", $id);
        		$this->db->update('notices', $post);
        		if ($this->db->affected_rows() == 0)
        		{
        		    return -1;
        		}

        		return $id;
            }
        }

        return 0;
    }
    
}

?>
