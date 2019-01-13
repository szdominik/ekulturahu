<?php
//Cikkekkel kapcsolatos műveletek vezérlője.
require_once('Base.php');
class Articles extends Base {

	//Konstruktor.
	public function __construct()
	{
		parent::__construct();
		$this->load->model('article_model');
		$GLOBALS['limit'] = 16;
	}

	//Keresés.
	public function search($filter = '', $from = 0)
	{
		$hdata['title'] = 'Keresés';
		$hdata['type'] = 'list';

		$data['limit'] = $GLOBALS['limit'];
		$data['from'] = intval($from);
		
		if($filter != '')
		{
			$data['search'] = urldecode($filter); //urldecode az ékezetes karakterek miatt
			
			$data['articles'] = $this->customize_articles(
				$this->article_model->get_searched_data($data['search'], $data['limit'], $data['from'], $data['cnt'])
			);

			$hdata['canonical_url'] = $this->generate_canonical_url(site_url(array('search', $filter)), $data['from']);
			$this->show('articles/index', $hdata, $data);
		}
		else
		{
			$data['search'] = $this->remove_non_searchable_chars($this->input->post('search'));
			if($data['search'] === '')
			{
				$this->show('home');
			}
			else
			{
				redirect('search/' . $data['search']);
			}
		}
	}
	
	//AJAX GET kérés: főmenü keresés mezője
	public function get_articles_by_search_short($filter)
	{
		$filter = urldecode($filter);
		$articles = $this->customize_articles($this->article_model->get_searched_data_short($filter, 10));
		ob_clean();
		echo json_encode($articles);
	}

	// keresést nehezítő karakterek (írásjelek, felesleges szóközök) törlése
	private function remove_non_searchable_chars($query)
	{
		return preg_replace('/\s\s+/', ' ', preg_replace('/[^\A-Za-zÀ-ÖØ-öø-ÿ\s]/', '', $query));
	}
	
	//Cikkmegjelenítés cikkszerző alapján.
	public function author_search($name, $from = 0)
	{
		$data['from'] = intval($from);
		$data['limit'] = $GLOBALS['limit'];
		$data['author'] = urldecode($name);
		$data['articles'] = $this->customize_articles(
			$this->article_model->get_articles_by_author($data['author'], $data['from'], $data['limit'])
		);

		if (empty($data['articles'])) //nincsenek cikkek: 404
		{
			$this->output->set_status_header('404');
		}
				
		$data['cnt'] = $this->article_model->get_articles_count_by_author($data['author']);
		$hdata['title'] = $data['author'] . ' cikkei';
		$hdata['type'] = 'list';
		$hdata['canonical_url'] = $this->generate_canonical_url(site_url(array('author', $name)), $data['from']);
		$this->show('articles/index', $hdata, $data);
	}

	//Metaadatok alapján történő cikkmegjelenítés.
	public function meta($type_slug, $slug, $from = 0)
	{
		$legacy_map = array(
			'szerzo-eloado-rendezo' => 'szerzo-rendezo',
			'szerzok' => 'szerzo-rendezo',
			'kiado' => 'kiado-forgalmazo',
			'fordito-k' => 'fordito',
			'szerkeszto-k' => 'szerkeszto',
			'zene' => 'zeneszerzo',
			'szereplok' => 'szereplo',
		);
		
		if (array_key_exists($type_slug, $legacy_map)) {
			$type_slug = $legacy_map[$type_slug];
		}

		$data['limit'] = $GLOBALS['limit'];
		if(intval($from) % $GLOBALS['limit'] !== 0) //3.0 linkproblémájára megoldás
			$data['from'] = 0;
		else
			$data['from'] = intval($from);
		
		$data['meta'] = $this->article_model->get_meta_by_slug($type_slug, $slug); //melyik meta alapján dolgozunk
		if($data['meta'] === FALSE)
		{
			if ($type_slug === 'cikk-tipus') {
				$this->output->set_status_header('410');
			} else {
				$this->output->set_status_header('404');
			}
			$hdata['title'] = 'Ismeretlen címke';
			$data['articles'] = array();
			$data['cnt'] = 0;
			
			$data['meta'] = array(
				'name'		=> 'ismeretlen',
				'type_slug' => $type_slug,
				'slug'		=> $slug
			);
		}
		else
		{
			$hdata['title'] = $data['meta']['name'];
			$hdata['type'] = 'list';
			
			$data['articles'] = $this->customize_articles(
				$this->article_model->get_articles_by_meta($data['meta']['id'], $data['limit'], $data['from'])
			);
			if (empty($data['articles'])) //ha üres, nincsenek cikkek itt: 404
			{
				$this->output->set_status_header('404');
			}
			
			$data['cnt'] = $this->article_model->get_articles_by_meta_count($data['meta']['id']);
		}

		$hdata['canonical_url'] = $this->generate_canonical_url(site_url(array('meta', $type_slug, $slug)), $data['from']);
		$this->show('articles/index', $hdata, $data);
	}
       
	//Egy kategória cikkeinek megjelenítése vagy statikus cikkmegjelenítés
	public function category_list($slug, $from = 0)
	{
		if ($slug == 'minden') {
			$slug = $from;
			$from = 0;
		}
		$data['from'] = intval($from);
		$data['limit'] = $GLOBALS['limit'];
		$hdata['canonical_url'] = $this->generate_canonical_url(site_url(array($slug)), $data['from']);

		$data['category'] = $this->article_model->get_categoryname_by_slug($slug);
		if (count($data['category']) != 0)
		{
			
			$data['articles'] = $this->customize_articles(
				$this->article_model->get_articles_by_category($data['from'], $data['limit'], $slug)
			);

			if (empty($data['articles'])) //nincsenek cikkek: 404
			{
				$this->output->set_status_header('404');
			}
					
			$data['cnt'] = $this->article_model->get_articles_count_by_category($slug);
			$hdata['title'] = $data['category']['name'];
			$hdata['type'] = 'list';
			$this->show('articles/index', $hdata, $data);
		}
		else
		{
			$data['subcategory'] = $this->article_model->get_subcategoryname_by_slug($slug);
			if (count($data['subcategory']) != 0)
			{
				$data['articles'] = $this->customize_articles(
					$this->article_model->get_articles_by_subcategory($data['from'], $data['limit'], $slug)
				);

				if (empty($data['articles'])) //nincsenek cikkek: 404
				{
					$this->output->set_status_header('404');
				}
						
				$data['cnt'] = $this->article_model->get_articles_count_by_subcategory($slug);
				$hdata['title'] = $data['subcategory']['name'];
				$hdata['type'] = 'list';
				$this->show('articles/index', $hdata, $data);
			}
			else
			{
				switch ($slug) {
					case 'napi_evfordulok':
						redirect('calendar', 'location', 301);
						break;
					case 'magunkrol':
						redirect('about', 'location', 301);
						break;
					case 'kapcsolat':
						redirect('contact', 'location', 301);
						break;
					case 'friss':
					case 'kategoriak':
					case 'ajanlott_szerzok':
					case 'esemenynaptar':
						$this->output->set_status_header('410');
						ob_clean();
						show_error($slug, 410);
						break;
					default:
						$this->static_view($slug);
						break;
				}
			}
		}
	}
	
	//Egy cikk megjelenítése.
	public function view($slug, $from = 0, $comment_success = -1)
	{
		$data['article'] = $this->article_model->get_article($slug);
		if (empty($data['article'])) //nincsenek cikkek: 404
		{
			$this->output->set_status_header('404');
			ob_clean();
			show_404($slug);
		}
		else 
		{
			$data['article'] = $this->customize_one_article($data['article']);
			if($comment_success != -1)
				$data['success'] = $comment_success;
			// $data['comments'] = $this->article_model->get_comments($data['article']['id']);
			$data['metas'] = $this->article_model->get_metas_by_article($data['article']['id']);
			$hdata['title'] = $data['article']['title'];
			$hdata['type'] = 'article';
			$hdata['short_body'] = $data['article']['short_body'];
			$hdata['image_path'] = $data['article']['image_path'];
			$hdata['author'] = $data['article']['user_name'];
			$hdata['metas'] = '';
			for ($i = 0; $i < count($data['metas']); ++$i) {
				$hdata['metas'] .= $data['metas'][$i]['meta_name'];
				if ($i !== count($data['metas']) - 1) {
					$hdata['metas'] .= ', ';
				}
			}
			$hdata['pub_time'] = substr(str_replace('. ', '-', $data['article']['pub_time']), 0, 10);
			$hdata['canonical_url'] = $data['article']['link'];
			$this->show('articles/view', $hdata, $data);
		}
	}
	
	//Hozzászólások kezelése. Ezzel egy POST kérésen át kezdünk foglalkozni.
	//Működés felfüggesztve 2017.07.12.
	/*
	public function article_comment($slug)
	{
		$this->load->library('form_validation');

		$data['ac_item'] = $this->article_model->get_article($slug);
		if (empty($data['ac_item'])) //nincsenek cikkek: 404
		{
			$this->output->set_status_header('404');
		}

		$this->form_validation->set_rules('comment', 'hozzászólás', 'trim|max_length[3000]');
		//a 2. feltétel mondjuk felesleges, mert itt már mindig kommentet akarunk küldeni
		if ($this->form_validation->run() === TRUE && $this->input->post('save') === 'comment_send')
		{
			//komment adatai
			$comment = array(
				'user_id' => $this->session->userdata('id'),
				'article_id' => $data['ac_item']['id'],
				'date' => $this->article_model->datetimeNow(),
				'body' => $this->input->post('comment'),
			);
			if($this->article_model->comment_insert($comment))
				redirect(current_url());
		}
		//ha hibába futottunk
		$this->view($slug, 0, 0);
	}*/
	
	//AJAX GET: kapcsolódó tartalmak
	public function get_other_articles_by_meta_id($meta_id)
	{
		$articles = $this->customize_articles($this->article_model->get_other_articles_by_meta_id($meta_id));
		ob_clean();
		echo json_encode($articles);
	}
	
	//Statikus cikk megjelenítő.
	public function static_view($page)
	{
		$data['page'] = $this->article_model->get_info_from_static($page); //van-e ilyen útvonalú statikus oldal
		if ($data['page'] === NULL) //ha nincs: főoldal (és 404)
		{
			$this->output->set_status_header('404');
			ob_clean();
			show_404($page);
		}
		else
		{
			$hdata['title'] = $data['page']['title'];
			$hdata['type'] = 'list';
			$hdata['canonical_url'] = site_url(array($page));
			$this->show('articles/static_view', $hdata, $data);
		}
	}

	// RSS feed generálás
	public function rss()
	{
		$data['articles'] = $this->article_model->get_articles_by_category(0, 20, 'osszes');
		foreach ($data['articles'] as &$ac) {
			$ac['title'] = $this->escape_characters($ac['title']);
			$ac['link'] = $this->generate_link($ac);
			$ac['short_body'] = $this->escape_characters($this->generate_short_body($ac['body']));
			$ac['pub_time'] = date("D, d M Y H:i:s O", strtotime($ac['pub_time']));
		}
		ob_clean();
		$view = $this->load->view('pages/rss', $data, TRUE);
		$this->output
			->set_header("Content-Type: application/rss+xml; charset=UTF-8")
			->set_output($view);
	}

	// Karakterek escapelése
	private function escape_characters($body)
	{
		return str_replace(array('&'), array('&amp;'), $body);
	}

	// Kanonikus url generálás a megfelelő "from" értékkel
	private function generate_canonical_url($base_url, $from) {
		if ($from != 0) {
			return $base_url . '/' . $from;
		} else {
			return $base_url;
		}
	}
}