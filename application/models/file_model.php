<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class File_model extends CI_Model
{

    public function add($path, $name, $ext, $sample_id)
    {
        if ($sample_id == 0)
        {
            $user_id = $this->session->userdata["logged_in"]["userid"];
            $data = array('user_id' => $user_id);
            $this->db->insert('samples', $data);
		    $sample_id = $this->db->insert_id();
        }
        
        $data = array(
            'path' => $path,
            'name' => $name,
            'ext'  => $ext,
            'sample_id' => $sample_id
        );
        $this->db->insert('files', $data);
		$this->db->insert_id();
    }
    
    public function fetch($id)
    {
        $query = $this->db->select('name, path, sample_id')->from('files')->where('id', $id)->get();
		
		$results = array();
		if($query->num_rows() > 0)
		{
			foreach ($query->result() as $obj) 
			{
    			$results['name'] = $obj->name;
			    $results['path'] = $obj->path;
			    $results['sample_id'] = $obj->sample_id;
			}
		}
		
		return $results;
    }
    
    public function fetch_by_sample($sample_id)
    {
        $query = $this->db->select('name, path, id')->from('files')->where('sample_id', $sample_id)->get();
		
		$results = array();
		if($query->num_rows() > 0)
		{
			foreach ($query->result() as $obj) 
			{
    			$results['files'][$obj->id]['name'] = $obj->name;
			    $results['files'][$obj->id]['path'] = $obj->path;
			}
		}
		
		return $results;
    }
        
    public function fetch_by_filename($name)
    {
        $query = $this->db->select('path, sample_id, id')->from('files')->where('name', $name)->get();
        
        $results = array();
		if($query->num_rows() > 0)
		{
			foreach ($query->result() as $obj) 
			{
    			$results['files'][$obj->id]['path'] = $obj->path;
			    $results['files'][$obj->id]['sample_id'] = $obj->sample_id;
			}
		}
		
		return $results;
    }
    
    public function delete($id)
    {
        $file = $this->fetch($id);
        $this->db->delete('files', array('id' => $id));
        unlink($file["path"]);
        return $file['name'];
    }
}

?>
