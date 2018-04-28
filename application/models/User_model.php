<?php
//A felhaszn�l�k kezel�s�re szolg�l� modell (sz�rmaztatva a Base_Modelb�l)
require_once('Base_model.php');
class User_model extends Base_Model {

	//A User_model oszt�ly konstruktora
	public function __construct()
	{
		$this->load->database();
	}
	
	//Egy felhaszn�l� adatinak visszaad�sa az id alapj�n
	public function get_user_by_id($id)
	{
		$query = $this->db->get_where('users', array('id' => $id));
		return $query->row_array();
	}
	
	//Egy felhaszn�l� ment�se az adatb�zisban
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
	
	//Egy felhaszn�l� bel�p�se (felhaszn�l�n�v / jelsz� alapj�n)
	public function login($username, $password)
	{
		$this->db->where("username", $username);
		$this->db->where("password", $password);

		$query = $this->db->get("users");
		
		if($query->num_rows() > 0) //ha van ilyen adatokkal felhaszn�l�: bel�ptetj�k
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
			$this->session->set_userdata($login); //munkamenetbe ment�s
			return TRUE;
		}
		return FALSE;
	}
	
	//Felhaszn�l� adatinak friss�t�se
	public function update_user($id)
	{
		$data = array(  
			'name'		=> $this->input->post('name'),
			'username'	=> $this->input->post('username'),
			'email'		=> $this->input->post('email'),
		);
		
		$this->session->set_userdata($data); //ezt �t is vezetj�k a munkamenetbe
		if ($this->input->post('password') != '')
		{
			$data['password'] = $this->input->post('password');
		}

		$this->db->where('id', $id);
		return $this->db->update('users', $data);  
	}

	//Jelsz� ment�se (jelsz�eml�keztet� ut�n a gener�lt jelsz�t kell elmenteni adott e-mailc�mhez)
	public function update_password($email, $newpass)
	{		
		$this->db->where('email', $email);
		return $this->db->update('users', array('password' => $newpass)); 
	}

	//Annak vizsg�lata, hogy l�tezik-e  m�r ilyen e-mail c�m
	public function existing_email($email)
	{
		$this->db->where('email', $email);
		$query = $this->db->get("users");

		if($query->num_rows() > 0) //vagyis  == 1, teh�t l�tezik
		{
			return TRUE;
		}
		return FALSE;
	}
}