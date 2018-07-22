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
			'password'	=> password_hash($this->input->post('password'), PASSWORD_DEFAULT),
			'email'		=> $this->input->post('email'),
			'level'		=> 1
		);

		return $this->db->insert('users', $data);
	}
	
	//Egy felhaszn�l� bel�p�se (felhaszn�l�n�v / jelsz� alapj�n)
	public function login($username, $password)
	{
		$this->db->where('username', $username);
		$this->db->limit(1);
		$query = $this->db->get('users');
		
		if($query->num_rows() > 0) //ha van ilyen adatokkal felhaszn�l�: vizsg�ljuk a jelsz�t
		{
			$data = $query->row_array();
			if (password_verify($password, $data['password']) || hash_equals($data['password'], md5($password))) {
				$login = array(
					'id'		=> $data['id'],
					'name'		=> $data['name'],
					'username'	=> $data['username'],
					'level'		=> $data['level'],
					'email'		=> $data['email'],
					'logged_in'	=> TRUE
				);
				$this->session->set_userdata($login); //munkamenetbe ment�s
				return TRUE;
			}
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
			$data['password'] = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
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