<?php
//A felhasználókkal kapcsolatos műveletek vezérlője
require_once('Base.php');
class Users extends Base {

	//Konstruktor.
	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->model('user_model');
	}
	
	//Regisztráció.
	public function reg()
	{
		$this->load->library('form_validation');

		$hdata['title'] = 'Regisztráció';
		if($this->input->post('save') != NULL) //a validation errorokhoz szükséges -> volt-e már adatmozgás
		{
			$data['wasdata'] = TRUE;
		}
		else
		{
			$data['wasdata'] = FALSE;
		}

		$this->form_validation->set_rules('name', 'megjelenítendő név', 'trim');
		$this->form_validation->set_rules('username', 'felhasználónév', 'trim|required|is_unique[users.username]');
		$this->form_validation->set_rules('password', 'jelszó', 'trim|required|min_length[5]|matches[passconf]');
		$this->form_validation->set_rules('passconf', 'jelszó megerősítése', 'trim|required');
		$this->form_validation->set_rules('email', 'e-mail cím', 'trim|required|valid_email|is_unique[users.email]');
		$this->form_validation->set_rules('accept', 'feltételek', 'callback_must_check');

		if ($this->form_validation->run() === TRUE)
		{
			$data['success'] = $this->user_model->user_insert();
		}
		$this->show('users/reg', $hdata, $data);
	}
	
	//Belépés.
	public function login()
	{
		$this->load->library('form_validation');

		$hdata['title'] = 'Bejelentkezés';
		if($this->input->post('save') != NULL) //a validation errorokhoz szükséges -> volt-e már adatmozgás
		{
			$data['wasdata'] = TRUE;
		}
		else
		{
			$data['wasdata'] = FALSE;
		}
		
		$this->form_validation->set_rules('username', 'felhasználónév', 'trim|required');
		$this->form_validation->set_rules('password', 'jelszó', 'trim|required'); //min_length[5]

		if ($this->form_validation->run() === TRUE)
		{
			//van-e ilyen felhasználó, ha igen, léptessük be
			$result = $this->user_model->login($this->input->post('username'), $this->input->post('password'));
			
			if($result === TRUE) //ha minden rendben, mehet vissza az utoljára látogatott oldalra
			{
				redirect($this->input->post('current_url'));
			}
			else
			{
				$data['wrong_user'] = FALSE;
				$this->show('users/login', $hdata, $data);
			}
		}
		else
		{
			$this->show('users/login', $hdata, $data);
		}
	}
	
	//Beállítások módosítása.
	public function user_settings()
	{
		$this->load->library('form_validation');
		
		$hdata['title'] = 'Felhasználói beállítások';
		
		if($this->input->post('save') != NULL) //a validation errorokhoz szükséges -> volt-e már adatmozgás
		{
			$data['wasdata'] = TRUE;
		}
		else
		{
			$data['wasdata'] = FALSE;
		}
		
		$this->form_validation->set_rules('name', 'megjelenítendő név', 'trim');
		$this->form_validation->set_rules('username', 'felhasználónév', 'trim|callback_username_unique[username]');
		$this->form_validation->set_rules('password', 'jelszó', 'trim|matches[passconf]'); //min_length[5]|
		$this->form_validation->set_rules('passconf', 'jelszó megerősítése', 'trim');
		$this->form_validation->set_rules('email', 'e-mail cím', 'trim|valid_email|callback_email_unique[email]');

		if ($this->form_validation->run() === TRUE)
		{
			$data['success'] = $this->user_model->update_user($this->session->userdata('id'));
		}
		//Felhasználó adatainak lekérése adatbázisból
		$data['user'] = $this->user_model->get_user_by_id($this->session->userdata('id'));
		$this->show('users/user_settings', $hdata, $data);
	}
	
	//Jelszóemlékeztető.
	//Működés felfüggesztve 2017.07.12.
	/*
	public function passwd()
	{
		$this->load->library('form_validation');

		$hdata['title'] = 'Új jelszó igénylése';

		if($this->input->post('save') != NULL) //a validation errorokhoz szükséges -> volt-e már adatmozgás
		{
			$data['wasdata'] = TRUE;
		}
		else
		{
			$data['wasdata'] = FALSE;
		}
		
		$this->form_validation->set_rules('email', 'e-mail cím', 'trim|valid_email|callback_email_for_passwd[email]');

		if ($this->form_validation->run() === TRUE)
		{
			$this->load->helper('string');
					
			$new = random_string('alnum', 8); //random, 8 hosszú string generálása alfanumerikus karakterekből
			$data['success'] = $this->user_model->update_password($this->input->post('email'), sha1($new));
			if($data['success'] === TRUE)
			{
				$msg = 'Tisztelt Felhasználó!
						Új jelszava: ' . $new . '
						Üdvözlettel: ekultura.hu';
				$this->email($msg); //e-mailen való elküldés
			}
		}
		$this->show('users/passwd', $hdata, $data);
	}*/
	
	//Kijelentkezés.
	public function logout()
	{				
		$newdata = array(
			'user_id'		=> '',
			'user_username'	=> '',
			'user_name'		=> '',
			'user_email'	=> '',
			'user_level'	=> 0,
			'logged_in'		=> FALSE,
		);
			
		$this->session->unset_userdata($newdata); //munkamenet 'nullázása'
		$this->session->sess_destroy();
		
		redirect('/');
	}
	
	//E-mail küldés
	/*
	private function email($msg)
	{
			$this->load->helper('email');
			send_email($this->input->post('email'), 'Új jelszó igénylése', $msg);
			//Felkészítve a robosztusabb megoldásra is, ami persze élőben még nem működött soha
			$config = array(
				'protocol'  => 'smtp',
				'smtp_host' => 'smtp.gmail.com',
				'smtp_port' => 465,
				'smtp_user' => 'domiinikteszt@gmail.com',
				'smtp_pass' => 'teszt12345',
				'mailtype'  => 'html',
				'charset'   => 'iso-8859-2',
				'wordwrap'  => TRUE,
				'crlf'	 	=> '\r\n',
				'newline'   => '\r\n'
			);
			$this->load->library('email', $config);
			$this->email->from('domiinikteszt@gmail.com');
			$this->email->to($this->input->post('email'));
			$this->email->subject('Új jelszó igénylése');
			$this->email->message($msg);
			if($this->email->send())
			{
				echo 'Siker!';
			}
			else
			{
				show_error($this->email->print_debugger());
			}
	}*/
	
	//Form-validation callback vizsgálata: a felhasználónév egyedi-e
	function username_unique($data)
	{
		if($this->session->userdata('username') != $data)
		{
			$this->form_validation->set_rules('username', 'felhasználónév', 'is_unique[users.username]');
			return $this->form_validation->run();
		}
		return TRUE;
	}

	//Form-validation callback vizsgálata: az email cím egyedi-e
	function email_unique($data)
	{
		if($this->session->userdata('email') != $data)
		{
			$this->form_validation->set_rules('email', 'e-mail cím', 'is_unique[users.email]');
			return $this->form_validation->run();
		}
		return TRUE;
	}

	//Form-validation callback vizsgálata: szerepel-e az adatbázisban ez az email cím
	function email_for_passwd($email)
	{
		$this->form_validation->set_message('email_for_passwd', 'Az adatbázisunkban nem szerepel ez az e-mail cím!');
		return $this->user_model->existing_email($email);
	}
	
	//Form-validation callback vizsgálata: elfogadta-e a felhasználási feltételeket
	function must_check($check)
	{
		if ($check === '1')
		{
			return TRUE;	
		}
		
		$this->form_validation->set_message('must_check', 'A feltételek elfogadása kötelező!');
		return FALSE;
	}
}