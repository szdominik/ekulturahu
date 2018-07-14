<?php
//Alapműveletek vezérlője.
class Base extends CI_Controller {

	//Konstruktor.
	public function __construct()
	{
		parent::__construct();
		$this->load->model('base_model');
		$this->load->helper('cookie');
		if (get_cookie('ci_session') !== NULL) {
			$this->load->library('session');
		};
	}
	
	//A navigációs sávba szükséges adatok.
	private function get_headerdata()
	{
		$header['categories'] = $this->base_model->get_categories();
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

	private function get_userdata()
	{
		$user = array('logged_in' => FALSE);
		if ($this->load->is_loaded('session')) {
			$user['logged_in'] = $this->session->userdata('logged_in');
		}
		return $user;
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
		$hdata['title'] = 'home';
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
			ob_clean();
			//$this->statistics_insert();
			$header = $this->get_headerdata();
			$footer = $this->get_footerdata();
			$user = $this->get_userdata();
			$this->load->view('templates/header', array_merge($hdata, $header, $user)); //fejléc betöltése
			$this->load->view($location, array_merge($data, $user)); //tartalom betöltése
			if(strpos($location, 'admin') !== FALSE)
				$this->load->view('templates/scripts'); //adminfelület szkriptjeinek betöltése
			$this->load->view('templates/footer', array_merge($footer, $user)); //lábléc betöltése
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
			$article['user_link'] = $this->format_username($article);
			$article['short_body'] = $this->generate_short_body($article['body']);
		}
		$articles_array = $this->add_metacategory_to_articles($articles_array);
		return $articles_array;
	}

	//egy cikk testre szabása
	protected function customize_one_article($article)
	{
		$article['link'] = $this->generate_link($article);
		$article['pub_time'] = $this->modify_date($article['pub_time']);
		$article['user_link'] = $this->format_username($article);
		$article['short_body'] = $this->generate_short_body($article['body']);
		return $article;
	}

	//A cikk slug és pub_time mezője alapján link generálása
	//pl. 'feher_isten' (string) + 2015-06-02 21:04:30 (date) -> '2015/06/02/feher_isten' (string)
	// Ha ezt módosítod, van a Base_modelben is egy ilyen!
	protected function generate_link($article)
	{
		$link = site_url() . substr($article['pub_time'], 0, 4) . 
				'/' . substr($article['pub_time'], 5, 2) . '/' . substr($article['pub_time'], 8, 2) . 
				'/' . $article['slug'];
		return $link;
	}

  	// Törli a HTML tageket a szövegből, és a 200 utáni első szóközig adja vissza a szöveget.
	protected function generate_short_body($body)
	{
		$short_body = strip_tags($body);
		return substr($short_body, 0, strpos($short_body, ' ', 200)) . '...';
	}

	//A publikálási időt szebb formátumban adja vissza
	private function modify_date($date) 
	{
		return substr($date, 0, 4) . '. ' . substr($date, 5, 2) . '. ' .
				substr($date, 8, 2) . '. ' . substr($date, 11, 5);
	}

	private function format_username($article)
	{
		if($article['user_name'] != NULL)
			$user_link = anchor(array('author', urlencode($article['user_name'])), $article['user_name']);
		else
			$user_link = 'ekultura.hu';
		return $user_link; 
	}

	// A paraméterként megkapott cikkekhez hozzáadja a meta-kategóriájuk tömbjét
	private function add_metacategory_to_articles($articles)
	{
		$metas = $this->base_model->get_categories_metatype_for_articles(array_column($articles, 'id'));
		foreach ($articles as &$ac) {
			$filtered = array();
			foreach($metas as $meta) {
				if($meta['article_id'] === $ac['id']) {
					$filtered[] = $meta;
				}
			}

			$ac['meta_category'] = array();
			foreach($filtered as $fil) {
				$ac['meta_category'][] = array('name' => $fil['meta_name'], 'slug' => $fil['meta_slug']);
			}
		}
		return $articles;
	}
}