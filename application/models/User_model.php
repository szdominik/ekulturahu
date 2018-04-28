<?php
//A felhasználók kezelésére szolgáló modell (származtatva a Base_Modelbõl)
require_once('Base_model.php');
class User_model extends Base_Model {

	//A User_model osztály konstruktora
	public function __construct()
	{
		$this->load->database();
	}
	
	//Egy felhasználó adatinak visszaadása az id alapján
	public function get_user_by_id($id)
	{
		$query = $this->db->get_where('users', array('id' => $id));
		return $query->row_array();
	}
	
	//Egy felhasználó mentése az adatbázisban
	public function user_insert()
	{
		$data = array(
			'name'		=> $this->input->post('name'),
			'username'	=> $this->input->post('username'),
			'password'	=> $this->input->post('password'),
			'email'		=> $this->input->post('email'),
			'level'		=> 1
		);

		return $this->db->insert('users', $data);
	}
	
	//Egy felhasználó belépése (felhasználónév / jelszó alapján)
	public function login($username, $password)
	{
		$this->db->where("username", $username);
		$this->db->where("password", $password);

		$query = $this->db->get("users");
		
		if($query->num_rows() > 0) //ha van ilyen adatokkal felhasználó: beléptetjük
		{
			foreach($query->result() as $rows)
			{
				$login = array(
					'id'		=> $rows->id,
					'name'		=> $rows->name,
					'username'	=> $rows->username,
					'level'		=> $rows->level,
					'email'		=> $rows->email,
					'logged_in'	=> TRUE
				);
			}
			$this->session->set_userdata($login); //munkamenetbe mentés
			return TRUE;
		}
		return FALSE;
	}
	
	//Felhasználó adatinak frissítése
	public function update_user($id)
	{
		$data = array(  
			'name'		=> $this->input->post('name'),
			'username'	=> $this->input->post('username'),
			'email'		=> $this->input->post('email'),
		);
		
		$this->session->set_userdata($data); //ezt át is vezetjük a munkamenetbe
		if ($this->input->post('password') != '')
		{
			$data['password'] = $this->input->post('password');
		}

		$this->db->where('id', $id);
		return $this->db->update('users', $data);  
	}

	//Jelszó mentése (jelszóemlékeztetõ után a generált jelszót kell elmenteni adott e-mailcímhez)
	public function update_password($email, $newpass)
	{		
		$this->db->where('email', $email);
		return $this->db->update('users', array('password' => $newpass)); 
	}

	//Annak vizsgálata, hogy létezik-e  már ilyen e-mail cím
	public function existing_email($email)
	{
		$this->db->where('email', $email);
		$query = $this->db->get("users");

		if($query->num_rows() > 0) //vagyis  == 1, tehát létezik
		{
			return TRUE;
		}
		return FALSE;
	}
}