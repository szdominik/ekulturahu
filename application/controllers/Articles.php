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

		$data['limit'] = $GLOBALS['limit'];
		$data['from'] = intval($from);
		
		if($filter != '')
		{
			$data['search'] = urldecode($filter); //urldecode az ékezetes karakterek miatt
			
			$data['articles'] = $this->customize_articles($this->article_model->get_searched_data($data['search'], $data['limit'], $data['from'], $data['cnt']));
			//$data['cnt'] = $this->article_model->get_searched_data_count($data['search']);

			$this->show('articles/index', $hdata, $data);
		}
		else
		{
			$data['search'] = $this->input->post('search');
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
		$articles = $this->customize_articles($this->article_model->get_searched_data_short($filter, 5));
		ob_clean();
		echo json_encode($articles);
	}
	
	//Cikkmegjelenítés cikkszerző alapján.
	public function author_search($name, $from = 0)
	{
		$data['from'] = intval($from);
		$data['limit'] = $GLOBALS['limit'];
		$data['author'] = urldecode($name);
		$data['articles'] = $this->customize_articles($this->article_model->get_articles_by_author($data['author'], $data['from'], $data['limit']));

		if (empty($data['articles'])) //nincsenek cikkek: 404
		{
			$this->output->set_status_header('404');
		}
				
		$data['cnt'] = $this->article_model->get_articles_count_by_author($data['author']);
		$hdata['title'] = $data['author'] . ' cikkei';
		$this->show('articles/index', $hdata, $data);
	}

	//Metaadatok alapján történő cikkmegjelenítés.
	public function meta($type_slug, $slug, $from = 0)
	{
		$data['limit'] = $GLOBALS['limit'];
		if(intval($from) % $GLOBALS['limit'] !== 0) //3.0 linkproblémájára megoldás
			$data['from'] = 0;
		else
			$data['from'] = intval($from);
		
		$data['meta'] = $this->article_model->get_meta_by_slug($type_slug, $slug); //melyik meta alapján dolgozunk
		if($data['meta'] === FALSE)
		{
			$hdata['title'] = 'Ismeretlen címke';
			$this->output->set_status_header('404');
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
			
			$data['articles'] = $this->customize_articles($this->article_model->get_articles_by_meta($data['meta']['id'], $data['limit'], $data['from']));
			if (empty($data['articles'])) //ha üres, nincsenek cikkek itt: 404
			{
				$this->output->set_status_header('404');
			}
			
			$data['cnt'] = $this->article_model->get_articles_by_meta_count($data['meta']['id']);
		}
		$this->show('articles/index', $hdata, $data);
	}
       
	//Egy kategória cikkeinek megjelenítése vagy statikus cikkmegjelenítés
	public function category_list($slug, $from = 0)
	{
		$data['from'] = intval($from);
		$data['limit'] = $GLOBALS['limit'];

		$data['category'] = $this->article_model->get_categoryname_by_slug($slug);
		if (count($data['category']) != 0)
		{
			
			$data['articles'] = $this->customize_articles($this->article_model->get_articles_by_category($data['from'], $data['limit'], $slug));

			if (empty($data['articles'])) //nincsenek cikkek: 404
			{
				$this->output->set_status_header('404');
			}
					
			$data['cnt'] = $this->article_model->get_articles_count_by_category($slug);
			$hdata['title'] = $data['category']['name'];
			$this->show('articles/index', $hdata, $data);
		}
		else
		{
			$data['subcategory'] = $this->article_model->get_subcategoryname_by_slug($slug);
			if (count($data['subcategory']) != 0)
			{
				$data['articles'] = $this->customize_articles($this->article_model->get_articles_by_subcategory($data['from'], $data['limit'], $slug));

				if (empty($data['articles'])) //nincsenek cikkek: 404
				{
					$this->output->set_status_header('404');
				}
						
				$data['cnt'] = $this->article_model->get_articles_count_by_subcategory($slug);
				$hdata['title'] = $data['subcategory']['name'];
				$this->show('articles/index', $hdata, $data);
			}
			else
			{
				$this->static_view($slug);
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
			//$this->show();
			show_404($slug);
		}
		else 
		{
			$data['article'] = $this->customize_one_article($data['article']);
			if($comment_success != -1)
				$data['success'] = $comment_success;
			$data['comments'] = $this->article_model->get_comments($data['article']['id']);
			$data['metas'] = $this->article_model->get_metas_by_article($data['article']['id']);
			$hdata['title'] = $data['article']['title'];
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
			//$this->show();
			show_404($page);
		}
		else
		{
			$hdata['title'] = $data['page']['title'];
			$this->show('articles/static_view', $hdata, $data);
		}
	}

	public function rss()
	{
		$data['articles'] = $this->article_model->get_articles_by_category(0, 20, 'osszes');
		foreach ($data['articles'] as &$ac) {
			$ac['link'] = $this->generate_link($ac);
			$ac['short_body'] = $this->generate_short_body($ac['body']);
			$ac['pub_time'] = date("D, d M Y H:i:s O", strtotime($ac['pub_time']));
		}
		ob_clean();
		$view = $this->load->view('pages/rss', $data, TRUE);
		$this->output
			->set_header("Content-Type: application/rss+xml; charset=UTF-8")
			->set_output($view);
	}
}