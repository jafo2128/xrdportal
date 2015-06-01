<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model
{
    
    public function login($username, $password)
    {
		$this->db->select('id, fname, lname, email, admin');
	
        $this->db->from('users');
        $this->db->where('email', $username);
        $this->db->where('password', hash('sha256', $password));
		$this->db->limit(1);
		
        $query = $this->db->get();
        
		if ($query->num_rows() == 1)
		{
			return $query->result();
		}
		else
		{
			return false;
		}
    }
	
	public function get_user_list()
	{
	    $this->db->select("id, fname, lname");
	    $this->db->from("users");
	    $this->db->where("admin", "0");
	    $this->db->where("active", "1");
	    $query = $this->db->get();
	    
	    $users[0] = "-";
        if ($query->num_rows() > 0)
        {
            foreach ($query->result() As $obj) 
			{
                $users[$obj->id] = $obj->fname." ".$obj->lname;
            }
        }
        return $users;
	}
	
	public function get_users()
	{
	    $this->db->select('id, student_no, fname, lname, email');
		$this->db->from('users');
		$this->db->where('active', 1);
		$this->db->order_by("student_no", "asc");
		$query = $this->db->get();
		
		$results['headers'] = array('First Name', 'Last Name', 'Email', 'Student #', '');

		$datamodels = array();
		if($query->num_rows() > 0)
		{
		    $results['rows'] = array(); 
			foreach ($query->result() as $obj) 
			{

			    $r["id"] = $obj->id;
			    $r["fname"] = $obj->fname;
			    $r["lname"] = $obj->lname;
			    $r["email"] = $obj->email;
			    $r["studentno"] = $obj->student_no;
			    
				$results['rows'][] = $r;
			}
		}
		
		return $results;
	}
	
	public function get_user($id)
	{
		$admin = $this->session->userdata["logged_in"]["admin"];
		if ($admin)
		{
		    $query = $this->db->select()->from("users")->where("id", $id)->get();
		    $result = $query->result();
    		return $result[0];
		}
	}
	
	public function update_user($post)
	{  	
    	$admin = $this->session->userdata["logged_in"]["admin"];
	    if ($admin && (!isset($post['id']) || $post['id'] == 0)) {
	        if (isset($post['id'])) unset($post['id']);
	        $this->db->insert('users', $post);
	        return $this->db->insert_id();
        }
        else {
            $id = $post['id'];
        	unset($post['id']);

            $this->db->where("id", $id);
    		$this->db->update('users', $post);
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
	    $data = array('active' => 0);
	    $this->db->where('id', $id);
	    $this->db->update('users', $data);
	    if ($this->db->affected_rows() == 0)
		{
		    return 0;
		}
		return 1;
	}
}
?>
