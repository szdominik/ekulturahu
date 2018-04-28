<?php
//Alapműveletek vezérlője.
class Base extends CI_Controller {

	//Konstruktor.
	public function __construct()
	{
		parent::__construct();
		$this->load->model('base_model');
	}
	
	//A navigációs sávba szükséges adatok.
	private function get_headerdata()
	{
		//$header['categories'] = $this->base_model->get_categories();
		$header['subcategories'] = $this->base_model->get_subcategories();
		//$header['conns'] = $this->base_model->get_connections_by_categories();
		return $header;
	}
	
	//A láblécbe szükséges adatok.
	private function get_footerdata()
	{
		$footer['statics'] = $this->base_model->get_statics();
		return $footer;
	}
	
	//Statisztikai adat beszúrása.
	private function statistics_insert()
	{
		$this->load->library('user_agent');
		if (! $this->agent->is_robot())
		{
			if(is_numeric($this->uri->segment(1)))
				$uri_string = $this->uri->segment(4);
			elseif(is_numeric($this->uri->segment(2)))
				$uri_string = $this->uri->segment(1);
			else
				$uri_string = (uri_string() === '') ? 'home' : uri_string();
			
			if(strpos($uri_string, 'assets') === FALSE)
				$this->base_model->statistics_insert($uri_string, $this->input->ip_address());
		}
	}
	
	//Főoldal megjelenítése.
	public function home()
	{
		$hdata['title'] = 'Főoldal';
		$data['articles'] = $this->customize_articles($this->base_model->get_articles_for_mainpage());
		$this->show('pages/home', $hdata, $data);
	}

	//Ugrás a megfelelő oldalra.
	protected function show($location = 'home', $hdata = array('title' => "Főoldal"), $data = array())
	{
		if($location == 'home') //alapértelmezett érték
		{
			$this->home();
		}
		else
		{
			//$this->statistics_insert();
			$header = $this->get_headerdata();
			$footer = $this->get_footerdata();
			$result = array_merge($hdata, $header); //a $hdata-ban adunk meg címet, ezt kombináljunk a kapott értékekkel
			$this->load->view('templates/header', $result); //fejléc betöltése
			$this->load->view($location, $data); //tartalom betöltése
			if(strpos($location, 'admin') !== FALSE)
				$this->load->view('templates/scripts'); //adminfelület szkriptjeinek betöltése
			$this->load->view('templates/footer', $footer); //lábléc betöltése
		}
	}
	
	//A különböző címek megfelelő 'útvonalasítása'. Az url_title előtt cseréljük az ékezetes karaktereket (különben kihagyná).
	protected function url_slugging($string)
	{
		$this->load->helper('text');
		$slug = url_title(convert_accented_characters($string), '-', TRUE);
		return $slug;
	}
	
	//cikkek tömbjének testre szabása: bővítése linkkel, dátum módosítása
	protected function customize_articles($articles_array)
	{
		foreach($articles_array as &$article) {
			$article['link'] = $this->generate_link($article);
			$article['pub_time'] = $this->modify_date($article['pub_time']);
			$article['user_link'] = $this->add_username($article);
		}
		return $articles_array;
	}

	//egy cikk testre szabása
	protected function customize_one_article($article)
	{
		$article['link'] = $this->generate_link($article);
		$article['pub_time'] = $this->modify_date($article['pub_time']);
		$article['user_link'] = $this->add_username($article);
		return $article;
	}

	//A cikk slug és pub_time mezője alapján link generálása
	//pl. 'feher_isten' (string) + 2015-06-02 21:04:30 (date) -> '2015/06/02/feher_isten' (string)
	protected function generate_link($article)
	{
		$link = site_url() . substr($article['pub_time'], 0, 4) . 
				'/' . substr($article['pub_time'], 5, 2) . '/' . substr($article['pub_time'], 8, 2) . 
				'/' . $article['slug'];
		return $link;
	}

	//A publikálási időt szebb formátumban adja vissza
	private function modify_date($date) 
	{
		return substr($date, 0, 4) . '. ' . substr($date, 5, 2) . '. ' .
				substr($date, 8, 2) . '. ' . substr($date, 11, 5);
	}

	private function add_username($article)
	{
		if($article['user_name'] != NULL)
			$user_link = anchor(array('author', urlencode($article['user_name'])), $article['user_name']);
		else
			$user_link = 'ekultura.hu';
		return $user_link; 
	}
}