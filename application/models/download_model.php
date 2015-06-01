<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Download_model extends CI_Model
{

    public function add($name, $sample_id)
    {
        $user_id = $this->session->userdata["logged_in"]["userid"];
        $data = array(
            'name' => $name,
            'sample_id' => $sample_id,
            'user_id' => $user_id
        );
        $this->db->insert('downloads', $data);
    }   
    
}

?>
