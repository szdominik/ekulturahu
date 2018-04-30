<?php
//Adminisztrációs felülettel kapcsolatos műveletek vezérlője.
require_once('Base.php');
class Admin extends Base {

	//Konstruktor.
	public function __construct()
	{
		parent::__construct();
		$this->load->model('admin_model');
	}
	
	//Alapadatok lekérése a cikkekhez.
	private function get_datas()
	{
		$writers = $this->admin_model->get_writer_users();
		$data['writers'] = array(
			'ures' => 'Nincs kiválasztva!'
		);
		foreach($writers as $wr)
		{
			$data['writers'][$wr['id']] = $wr['name'];
		}
		
		$categories = $this->admin_model->get_categories();
		$data['categories'] = array(
			'ures' => 'Nincs kiválasztva!'
		);
		foreach($categories as $cat)
		{
			$data['categories'][$cat['id']] = $cat['name'];
		}
		
		$subcategories = $this->admin_model->get_subcategories();
		$data['subcategories'] = array(
			'ures' => 'Nincs kiválasztva!'
		);
		foreach($subcategories as $sc)
		{
			$data['subcategories'][$sc['id']] = $sc['name'];
		}

		$data['published_cats'] = array(
			'Nincs kiválasztva!',
			'Csak a publikáltak',
			'Csak a nem publikáltak'
		);
		return $data;
	}
	
	//Cikkek listázása.
	public function article_list($title = '0', $category = 0, $subcategory = 0, $published_cat = 0, $user = 0, $limit = 50, $from = 0)
	{				
		$data = $this->get_datas();
		
		//Szűrési opciók.
		if($this->input->post('title') != NULL)
			$data['title'] = $this->input->post('title');
		else
		{
			$data['title'] = urldecode($title);
		}
		
		if($this->input->post('category') != NULL)
		{
			$data['category'] = $this->input->post('category');
		}
		else
		{
			$data['category'] = $category;
		}
		
		if($this->input->post('subcategory') != NULL)
		{
			$data['subcategory'] = $this->input->post('subcategory');
		}
		else
		{
			$data['subcategory'] = $subcategory;
		}

		if($this->input->post('published_cat') != NULL)
		{
			$data['published_cat'] = $this->input->post('published_cat');
		}
		else
		{
			$data['published_cat'] = $published_cat;
		}
		
		if($this->session->userdata('level') == 3)
		{
			$data['user'] = $this->session->userdata('id');
		}
		elseif($this->input->post('user') != NULL)
		{
			$data['user'] = $this->input->post('user');
		}
		else
		{
			$data['user'] = $user;
		}
		
		if($this->input->post('limit') != NULL)
		{
			$data['limit'] = $this->input->post('limit');
		}
		else
		{
			$data['limit'] = $limit;
		}

		//Ezek alapján a cikk lekérése.
		$data['articles'] = $this->admin_model->get_articles_for_editlist($data['title'], $data['category'], $data['subcategory'], $data['published_cat'], $data['user'], $data['limit'], $from);
		$data['articles'] = $this->customize_articles($data['articles']);
		$data['cnt'] = $this->admin_model->get_articles_for_editlist_count($data['title'], $data['category'], $data['subcategory'], $data['published_cat'], $data['user'], $data['limit'], $from);
		
		$hdata['title'] = 'Cikkek szerkesztése';
		$data['from'] = $from;
		$this->show('admin/article_list', $hdata, $data);
	}
	
	//Új cikk.
	public function article_new()
	{
		$this->load->library('form_validation');
		
		$data = $this->get_datas();
		$slug = $this->url_slugging($this->input->post('title'));

		$this->form_validation->set_rules('title', 'cím', 'trim|required|callback_slug_unique['.$slug.']');
		$this->form_validation->set_rules('category', 'főkategória', 'trim|callback_must_choose[főkategória]');
		$this->form_validation->set_rules('subcategory', 'alkategória', 'trim|callback_must_choose[alkategória]');
		if ($this->session->userdata('level') >= 3)
		{
			$this->form_validation->set_rules('user', 'cikkszerző', 'trim|callback_must_choose[cikkszerző]');
		}
		$this->form_validation->set_rules('pub_time', 'dátum', 'required');
		$this->form_validation->set_rules('kedv_vasar', 'kedvezményes vásár', 'trim');
		$this->form_validation->set_rules('eredeti_cim', 'eredeti cím', 'trim');
		$this->form_validation->set_rules('ar', 'ár', 'trim');
		$this->form_validation->set_rules('terjedelem', 'terjedelem', 'trim');
		$this->form_validation->set_rules('forgatokonyviro', 'forgatókönyvíró', 'trim');
		$this->form_validation->set_rules('operator', 'operatőr', 'trim');
		$this->form_validation->set_rules('producer', 'producer', 'trim');
		$this->form_validation->set_rules('body', 'főszöveg', 'trim|max_length[65000]');
		$this->form_validation->set_rules('userfile', 'kép feltöltése', 'callback_image_upload');

		if ($this->form_validation->run() === FALSE) //nem sikerült a form validálás
		{
			$hdata['title'] = 'Új cikk';
			
			if($this->input->post('save') != NULL) //a validation errorokhoz szükséges -> volt-e már adatmozgás
			{
				$data['wasdata'] = TRUE;
			}
			else
			{
				$data['wasdata'] = FALSE;
			}
			$data['image_upload'] = $this->image_upload_filename();
			$this->show('admin/article_new', $hdata, $data);
		}
		else //jó volt a form-validálás
		{
			$id_data = array(
				'slug' 			=> $slug,
				'image_path' 	=> $this->image_upload_filename(),
			);
			$id = $this->admin_model->article_insert($id_data);
			$this->article_edit($id, TRUE);
		}
	}
	  
	//Cikk szerkesztése.
	public function article_edit($id, $succ = FALSE)
	{
		$this->load->library('form_validation');
		
		$data = $this->get_datas();

		$slug = $this->url_slugging($this->input->post('title'));
		
		$this->form_validation->set_rules('title', 'cím', 'trim|required|callback_slug_unique['.$slug.']');
		$this->form_validation->set_rules('category', 'főkategória', 'trim|callback_must_choose[főkategória]');
		$this->form_validation->set_rules('subcategory', 'alkategória', 'trim|callback_must_choose[alkategória]');
		if ($this->session->userdata('level') >= 3)
		{
			$this->form_validation->set_rules('user', 'cikkszerző', 'trim|callback_must_choose[cikkszerző]');
		}
		$this->form_validation->set_rules('pub_time', 'dátum', 'required');
		$this->form_validation->set_rules('kedv_vasar', 'kedvezményes vásár', 'trim');
		$this->form_validation->set_rules('eredeti_cim', 'eredeti cím', 'trim');
		$this->form_validation->set_rules('ar', 'ár', 'trim');
		$this->form_validation->set_rules('terjedelem', 'terjedelem', 'trim');
		$this->form_validation->set_rules('forgatokonyviro', 'forgatókönyvíró', 'trim');
		$this->form_validation->set_rules('operator', 'operatőr', 'trim');
		$this->form_validation->set_rules('producer', 'producer', 'trim');
		$this->form_validation->set_rules('body', 'főszöveg', 'trim|max_length[65000]');
		$this->form_validation->set_rules('userfile', 'kép feltöltése', 'callback_image_upload');
		
		$hdata['title'] = 'Cikk szerkesztése';
		if ($succ == TRUE || $this->form_validation->run() === FALSE) //valamely művelet sikere után értünk ide, vagy most vagyunk itt először, vagy sikertelen volt a form validation
		{
			if($this->input->post('save') != NULL) //a validation errorokhoz szükséges -> volt-e már adatmozgás
			{
				$data['wasdata'] = TRUE;
			}
			else
			{
				$data['wasdata'] = FALSE;
			}
				
			if($this->input->post('id') == NULL) //ez az első alkalom, hogy be akarjuk tölteni a formot
			{
				$data['article'] = $this->admin_model->get_article_by_id($id);
			}
			else //hibás volt a form
			{
				$data['article']['id'] = $this->input->post('id');
				$data['article']['slug'] = $this->input->post('slug');
				$data['article']['title'] = $this->input->post('title');
				$data['article']['category_id'] = $this->input->post('category');
				$data['article']['subcategory_id'] = $this->input->post('subcategory');
				$data['article']['published'] = $this->input->post('published');
				$data['article']['pub_time'] = $this->input->post('pub_time');
				$data['article']['mainpage'] = $this->input->post('mainpage');
				$data['article']['comment'] = $this->input->post('comment');
				$data['article']['login'] = $this->input->post('login');
				$data['article']['user_id'] = $this->input->post('user');
				$data['article']['kedv_vasar'] = $this->input->post('kedv_vasar');
				$data['article']['eredeti_cim'] = $this->input->post('eredeti_cim');
				$data['article']['ar'] = $this->input->post('ar');
				$data['article']['terjedelem'] = $this->input->post('terjedelem');
				$data['article']['forgatokonyviro'] = $this->input->post('forgatokonyviro');
				$data['article']['operator'] = $this->input->post('operator');
				$data['article']['producer'] = $this->input->post('producer');
				$data['article']['body'] = $this->input->post('body');
				$data['article']['image_horizontal'] = $this->input->post('image_horizontal');
				$data['article']['image_path'] = $this->input->post('image_path');
			}
			
			$data['success'] = $succ;
		}
		else //sikeres form validation
		{
			$id_data = array(
				'slug'			=> $slug,
				'image_path'	=> $this->image_upload_filename(),
			);
			
			$data['success'] = $this->admin_model->article_update($id, $id_data);
			$data['article'] = $this->admin_model->get_article_by_id($id);
		}
		
		$data['article']['link'] = $this->generate_link($data['article']);
		$data['category'] = $this->admin_model->get_categoryname_by_id($data['article']['category_id']);
		$data['subcategory'] = $this->admin_model->get_subcategoryname_by_id($data['article']['subcategory_id']);
		$this->show('admin/article_edit', $hdata, $data);
	}
	
	//AJAX GET: metadatok egy cikkhez.
	public function get_metas_by_article($ac_id)
	{
		$article_metas = $this->admin_model->get_metas_by_article($ac_id);
		ob_clean();
		echo json_encode($article_metas);
	}
	
	//AJAX GET: metatípusok a cikkszerkesztéshez.
	public function get_metatypes()
	{
		$metas = $this->admin_model->get_metatypes();
		$metas[count($metas)] = array('name' => 'Nincs kiválasztva!', 'id' => 'ures');
		ob_clean();
		echo json_encode($metas);
	}
	
	//AJAX GET: metaadatok egy típus alapján a cikkszerkesztéshez.
	public function get_metas_by_type($type)
	{
		$metas = $this->admin_model->get_metas_by_type($type);
		ob_clean();
		echo json_encode($metas);
	}
	
	//AJAX POST: metaadat hozzáadása egy cikkhez.
	public function meta_edit_with_article()
	{
		$ac_id = $this->input->post('article_id');
		$type = $this->input->post('meta_type');
		$data = $this->input->post('meta_data');
		$value = trim($this->input->post('meta_value'));
		$slug = $this->url_slugging($value);

		if($type != 'ures') //kiválasztottuk-e típust
		{
			$article_metas = $this->admin_model->get_metas_by_article($ac_id); //milyen meták vannak már rajta
			if($value != '') //van-e valami a plusz mezőn, mert ha igen, azt használjuk
			{
				foreach($article_metas as $am) //ha a mostani metaadat már rajta van, nem rakjuk rá
				{
					if($am['type'] == $type && $am['name'] == $value)
					{
						$hiba = 'Ilyen metaadat már létezik az adott cikken!';
						break;
					}
				}
				if(! isset($hiba)) //ha nincs rajta, rátöltjük.
				{
					$voltmar = $this->admin_model->get_meta_by_slug_and_type($slug, $type);
					//a plusz mezőn lévő adat már létezik (ilyen típussal), ekkor használjuk a létezőt
					if($voltmar != FALSE)
					{
						$success = $this->admin_model->meta_value_article_insert($ac_id, $voltmar['id']);
					}
					//nem létezik, ekkor új feltöltése
					else
					{
						$meta = array(
							'name'	=> $value,
							'slug'	=> $slug,
							'type'	=> $type
						);
						$success = $this->admin_model->metavalue_insert($meta);
						if($success == TRUE)
						{
							$success = $this->admin_model->meta_value_article_insert($ac_id, $this->db->insert_id());
						}
						if($success != TRUE) //adatbázishiba? Nem sikerült a mentés.
						{
							$hiba = 'Sikertelen mentés! Kérem, próbálja újra!';
						}
					}
				}
			}
			else //a plusz mezőn nincs semmi, a legördülőben lévő használjuk
			{
				foreach($article_metas as $am) //létezik-e az adott metaadat már ezen a cikken
				{
					if($am['type'] == $type && $am['metavalue_id'] == $data)
					{
						$hiba = 'Ilyen metaadat már létezik az adott cikken!';
						break;
					}
				}
				if(! isset($hiba)) //nem létezik: rátöltés.
				{
					$success = $this->admin_model->meta_value_article_insert($ac_id, $data);
				}
			}
		}
		else
		{
			$hiba = 'Kötelező metaadat kategóriát választani!';
		}
		ob_clean();
		if(isset($hiba)) //ha volt hiba, írjuk ki a szöveget
		{
			echo $hiba;
		}
	}
	
	//AJAX: metaadat levétele a cikkről.
	public function meta_article_delete($mva_id)
	{
		$this->admin_model->meta_value_article_delete($mva_id);
	}
	
	//Kép törlése.
	public function image_delete($im_path, $attached_id, $what = 0)
	{
		$this->admin_model->image_delete($im_path, $attached_id, $what);
		if($what == 1) //eseményképet törlünk
		{
			$this->event_edit($attached_id);
		}
		else //cikképet törlünk (alapértelmezett)
		{
			$this->article_edit($attached_id, TRUE);
		}
	}
	
	//Cikk törlése.
	public function article_delete($id)
	{
		$this->admin_model->article_delete($id);
		redirect('admin/article_list');
	}
	
	//Statikus cikkek listázása (ha $succ nem 0, valami művelet sikere után értünk ide)
	public function static_list($succ = 0)
	{
		$hdata['title'] = 'Statikus cikkek szerkesztése';
		$data['statics'] = $this->admin_model->get_statics();
		if($succ != 0)
		{
			$data['success'] = $succ;
		}
		$this->show('admin/static_list', $hdata, $data);
	}
	
	//Új statikus cikk.
	public function static_new()
	{
		$this->load->library('form_validation');
		$hdata['title'] = 'Új statikus cikk';
		
		$this->form_validation->set_rules('title', 'cím', 'trim|required|max_length[100]');
		$this->form_validation->set_rules('path', 'útvonal', 'callback_static_slug_unique|trim|required|alpha_numeric|max_length[100]');
		if ($this->form_validation->run() === FALSE) //sikertelen validation
		{			
			if($this->input->post('save') != NULL) //a validation errorokhoz szükséges -> volt-e már adatmozgás
			{
				$data['wasdata'] = TRUE;
			}
			else
			{
				$data['wasdata'] = FALSE;
			}
			
			$this->show('admin/static_new', $hdata, $data);
		}
		else //sikeres form validation
		{
			$success = $this->admin_model->static_insert();
			if($success == FALSE)
			{
				$data['wasdata'] = TRUE;
				$this->show('admin/static_new', $hdata, $data);
			}
			else
			{
				$this->static_list($success);
			}
		}
	}
	
	//Statikus cikk szerkesztése.
	public function static_edit($id)
	{
		$this->load->library('form_validation');
			
		$this->form_validation->set_rules('title', 'cím', 'trim|required|max_length[100]');
		$this->form_validation->set_rules('path', 'útvonal', 'callback_static_slug_unique|trim|required|alpha_numeric|max_length[100]');
		if ($this->form_validation->run() === TRUE) //sikeres form validation - frissítés
		{
			$data['success'] = $this->admin_model->static_update($id);
		}
		else
		{
			$data['success'] = FALSE;
		}
		
		if($this->input->post('save') != NULL) //a validation errorokhoz szükséges -> volt-e már adatmozgás
		{
			//volt már adatmozgás: a megfelő adatokat töltsük vissza
			$data['wasdata'] = TRUE;
			$data['static']['id'] = $id;
			$data['static']['title'] = $this->input->post('title');
			$data['static']['path'] = $this->input->post('path');
			$data['static']['body'] = $this->input->post('body');
		}
		else
		{
			$data['wasdata'] = FALSE;
			$data['static'] = $this->admin_model->get_static_by_id($id);
		}
		
		$hdata['title'] = 'Statikus cikk szerkesztése';
		$this->show('admin/static_edit', $hdata, $data);
	}
	
	//Statikus cikk törlése.
	public function static_delete($id)
	{
		$this->admin_model->static_delete($id);
		redirect('admin/static_list');
	}
	
	//Metaadat lista.
	public function meta_list($meta_name = '0', $mtype = 0, $from = 0)
	{
		$this->load->library('form_validation');
		
		$data['from'] = $from;
		$data['limit'] = 50;
		$hdata['title'] = 'Címkék szerkesztése';
		
		$metatypes = $this->admin_model->get_metatypes();
		$data['metatype'] = array(
			'ures' => 'Nincs kiválasztva!'
		);
		foreach($metatypes as $mt)
		{
			$data['metatype'][$mt['id']] = $mt['name'];
		}

		//szűrés névre
		if($this->input->post('meta_name') != NULL)
			$data['meta_name'] = $this->input->post('meta_name');
		else
		{
			$data['meta_name'] = urldecode($meta_name);
		}
		
		//szűrés a típusra
		if($this->input->post('meta_filter') != NULL)
		{
			$data['meta_filter'] = $this->input->post('meta_filter');
		}
		else
		{
			$data['meta_filter'] = $mtype;
		}

		//szűrni akarunk - lapozásnál nincs post
		if($this->input->post('save') === 'filter' || $mtype != 0 || $meta_name != '0')
		{
			$data['metas'] = $this->admin_model->get_metas($data['limit'], $from, $data['meta_filter'], $data['meta_name']);
			$data['cnt'] = $this->admin_model->get_metas_count($data['meta_filter'], $data['meta_name']);
		}
		else
		{
			if($this->input->post('save') != NULL) //a validation errorokhoz szükséges -> volt-e már adatmozgás
			{
				$data['wasdata'] = TRUE;
			}
			else
			{
				$data['wasdata'] = FALSE;
			}
	
			$this->form_validation->set_rules('metatype', 'típus', 'trim|callback_must_choose[típus]');
			$this->form_validation->set_rules('metaname', 'név', 'trim|required|max_length[100]');
			if ($this->form_validation->run() === TRUE) //sikeres form-validation
			{
				$meta = array(
					'id'	=> $this->input->post('metaid'),
					'name'	=> $this->input->post('metaname'),
					'slug'	=> $this->url_slugging($this->input->post('metaname')),
					'type'	=> $this->input->post('metatype')
				);

				if($this->input->post('metaid') === '-1') //ha mág nincs id -> új beszúrása
				{
					//csak akkor szúrjuk be, ha még nincs ilyen
					if($this->admin_model->get_meta_by_slug_and_type($meta['slug'], $meta['type']) == FALSE)
					{
						$data['success'] = $this->admin_model->metavalue_insert($meta);
					}
					else
					{
						$data['success'] = FALSE;
					}
				}
				else //ha már van id: frissítés
				{
					//slugot ne frissítse
					unset($meta['slug']);
					$data['success'] = $this->admin_model->metavalue_update($meta);
				}
			}
			
			$data['metas'] = $this->admin_model->get_metas($data['limit'], $from);
			$data['cnt'] = $this->admin_model->get_metas_count();
		}
		$this->show('admin/meta_list', $hdata, $data);
	}
	
	//Metaadat törlése.
	public function meta_delete($id)
	{
		$this->admin_model->metavalue_delete($id);
		redirect('admin/meta_list');
	}
	
	//AJAX GET: metaadat lekérése.
	public function meta_get($id)
	{
		$data = $this->admin_model->get_meta_by_id($id);
		ob_clean();
		echo json_encode($data);
	}
	
	//Kategóriák listája.
	public function category_list($succ = FALSE)
	{
		$hdata['title'] = 'Kategóriák szerkesztése';
		$type = $this->input->post('type');
		
		if ($succ == TRUE)
			$data['success'] = TRUE;
		
		if($this->input->post('id') != '') //ha van id, akkor valamelyik kategórianevet frissíteni akarjuk
		{
			$update_data = array(
				'id'	=> $this->input->post('id'),
				'name'	=> $this->input->post('name'),
				'slug'	=> $this->url_slugging($this->input->post('name'))
			);
			$data['success'] = $this->admin_model->category_update($type, $update_data);
		}
		elseif($type != '') //ha nincs id, de van típus, akkor új beszúrás lesz
		{
			$insert_data = array(
				'name'	=> $this->input->post('name'),
				'slug'	=> $this->url_slugging($this->input->post('name'))
			);
			$data['success'] = $this->admin_model->category_insert($type, $insert_data);
		}
		
		$data['categories'] = $this->admin_model->get_categories_for_categorylist();
		$data['subcategories'] = $this->admin_model->get_subcategories_for_categorylist();
		$data['metatypes'] = $this->admin_model->get_metatypes_for_categorylist();
		$this->show('admin/category_list', $hdata, $data);
	}
	
	//Kategória törlése.
	public function category_delete($id, $type)
	{
		$this->admin_model->category_delete($type, $id);
		$this->category_list(TRUE);
	}
	
	//Működés felfüggesztve 2017.07.12.
	/*
	//Kategória-alkategória összekapcsolás
	public function category_connection()
	{
		$hdata['title'] = 'Kategória-alkategória összekapcsolás';
		
		$data['categories'] = $this->admin_model->get_categories();
		
		$this->show('admin/category_connection', $hdata, $data);
	}
	
	//AJAX GET: Kategória-alkategória összekapcsolások lekérése
	public function get_category_conns($cat_id)
	{
		$conns = $this->admin_model->get_category_connections_datas($cat_id);
		ob_clean();
		echo json_encode($conns);
	}
	
	//AJAX GET: lehetséges alkategóriák lekérése
	public function get_subcategories()
	{
		$subcategories = $this->admin_model->get_subcategories();
		$subcategories[count($subcategories)] = array('name' => 'Nincs kiválasztva!', 'id' => 'ures');
		ob_clean();
		echo json_encode($subcategories);
	}
	
	//AJAX POST: Kategória-alkategória összekapcsolás hozzáadása
	public function category_conn_add()
	{
		$cat = $this->input->post('category_id');
		$subcat = $this->input->post('subcategory_id');
		
		$this->admin_model->sub_cat_connect_insert($cat, $subcat);
	}
	
	//AJAX: összekapcsolás levétele a kategóriáról.
	public function category_conn_delete($cat, $subcat)
	{
		$this->admin_model->sub_cat_connect_delete($cat, $subcat);
	}
	*/
	
	//Felhasználók listája.
	public function user_list($level = 0, $from = 0, $succ = FALSE)
	{
		$this->load->library('form_validation');
		
		$levels = $this->admin_model->get_levels(); //szintek lekérése
		$data['level'] = array(
			'ures' => 'Nincs kiválasztva!'
		);
		foreach($levels as $lvl)
		{
			$data['level'][$lvl['id']] = $lvl['name'];
		}
		
		$data['from'] = $from;
		$data['limit'] = 50;
		$hdata['title'] = 'Felhasználók szerkesztése';
		
		if($this->input->post('save') != NULL) //a validation errorokhoz szükséges -> volt-e már adatmozgás
		{
			$data['wasdata'] = TRUE;
		}
		else
		{
			$data['wasdata'] = FALSE;
		}
		
		//Szűrési adat.
		if($level == 0)
		{
			$data['level_filt'] = $this->input->post('level_filt');
		}
		else
		{
			$data['level_filt'] = $level;
		}
		
		$this->form_validation->set_rules('level', 'level', 'trim|callback_must_choose[csoport]');
		if ($this->form_validation->run() === TRUE) //sikeres form validation: valamit akarunk csinálni és jól csináljuk
		{
			if($this->input->post('save') === 'filter') //szűrni akarunk
			{
				$data['users'] = $this->admin_model->get_users($data['limit'], $from, $data['level_filt']);
				$data['cnt'] = $this->admin_model->get_users_count($data['level_filt']);
			}
			else //nem szűrés, tehát felhasználói szint frissítés
			{
				$data['success'] = $this->admin_model->user_update_with_level($this->input->post('username'), $this->input->post('level'));
				$data['cnt'] = $this->admin_model->get_users_count($this->input->post('level'));
				$data['users'] = $this->admin_model->get_users($data['limit'], $from);
			}
		}
		else //sikertelen from validation (vagy először járunk itt)
		{
			if($level != 0) //szűrni akarunk
			{
				$data['users'] = $this->admin_model->get_users($data['limit'], $from, $data['level_filt']);
				$data['cnt'] = $this->admin_model->get_users_count($data['level_filt']);
			}
			else
			{
				$data['cnt'] = $this->admin_model->get_users_count();
				$data['users'] = $this->admin_model->get_users($data['limit'], $from);
			}
			$data['success'] = $succ;
		}
		$this->show('admin/user_list', $hdata, $data);
	}
	
	//AJAX GET: felhasználó adatainak lekérése.
	public function user_get($id)
	{
		$data = $this->admin_model->get_user_by_id($id);
		ob_clean();
		echo json_encode($data);
	}
	
	//Felhasználó törlése.
	public function user_delete($id)
	{
		$this->admin_model->user_delete($id);
		$this->user_list(0, 0, TRUE);
	}
	
	//Kommentek listája.
	public function comment_list($comm = "0", $from = 0)
	{
		$this->load->library('form_validation');
		
		$data['from'] = $from;
		$data['limit'] = 50;
		$hdata['title'] = 'Hozzászólások szerkesztése';

		//Szűrési adat.
		if($comm == "0")
		{
			$data['comm_filt'] = $this->input->post('comm_filt');
		}
		else
		{
			$data['comm_filt'] = urldecode($comm);
		}
		
		if($this->input->post('save') != NULL) //a validation errorokhoz szükséges -> volt-e már adatmozgás
		{
			$data['wasdata'] = TRUE;
		}
		else
		{
			$data['wasdata'] = FALSE;
		}
		
		if($this->session->userdata('level') > 3) //ha legalább 4-es szintűek vagyunk, minden kommentet láthatunk
		{
			$user = 0;
		}
		else //különben csak a saját hozzászólásainkat
		{
			$user = $this->session->userdata('id');
		}
		
		$this->form_validation->set_rules('comm_body', 'hozzászólás', 'trim|max_length[1000]');
		if ($this->form_validation->run() === TRUE) //sikeres form validation
		{
			if($this->input->post('save') === 'filter') //szűrni akarunk
			{
				$data['comments'] = $this->admin_model->get_comments($user, $data['limit'], $from, $data['comm_filt']);
				$data['cnt'] = $this->admin_model->get_comments_count($user, $data['comm_filt']);
			}
			else //hozzászólást akarunk frissíteni
			{
				$data['success'] = $this->admin_model->comment_update($this->input->post('comm_id'), $this->input->post('comm_body'));
				$data['comments'] = $this->admin_model->get_comments($user, $data['limit'], $from);
				$data['cnt'] = $this->admin_model->get_comments_count($user);
			}
		}
		else //sikertelen form validation
		{
			if($comm != "0") //szűrni akarunk
			{
				$data['comments'] = $this->admin_model->get_comments($user, $data['limit'], $from, $data['comm_filt']);
				$data['cnt'] = $this->admin_model->get_comments_count($user, $data['comm_filt']);
			}
			else
			{
				$data['comments'] = $this->admin_model->get_comments($user, $data['limit'], $from);
				$data['cnt'] = $this->admin_model->get_comments_count($user);
			}
			$data['success'] = FALSE;
		}
		$data['comments'] = $this->customize_articles($data['comments']);
		$this->show('admin/comment_list', $hdata, $data);
	}
	
	//Hozzászólás törlése.
	public function comment_delete($id)
	{
		$this->admin_model->comment_delete($id);
		redirect('admin/comment_list');
	}
	
	//AJAX GET: hozzászólás lekérése.
	public function comment_get($id)
	{
		$comment = $this->admin_model->get_comment_by_id($id);
		ob_clean();
		echo json_encode($comment);
	}
	
	//Események listája.
	//Működés felfüggesztve 2017.07.21.
	/*public function event_list($bgn = "0", $end = "0", $from = 0, $succ = 0)
	{
		$this->load->library('form_validation');

		$hdata['title'] = 'Események szerkesztése';
		$data['limit'] = 50;
		$data['from'] = $from;
		
		if($this->input->post('save') == 'filter') //szűrni akarunk űrlap alapján
		{
			$data['bgn'] = $this->input->post('filter_begin');
			$data['end'] = $this->input->post('filter_end');
		}
		else //nincs szűrés vagy link alapján szűrünk 
		{
			$data['bgn'] = urldecode($bgn);
			$data['end'] = urldecode($end);
		}

		if($this->session->userdata('level') == 2 || $this->session->userdata('level') == 3) //2-es vagy 3-as felhasználók csak a saját eseményeiket látják
		{
			$data['events'] = $this->admin_model->get_events($data['bgn'], $data['end'], $data['limit'], $data['from'], $this->session->userdata('id'));
			$data['cnt'] = $this->admin_model->get_events_count($data['bgn'], $data['end'], $this->session->userdata('id'));
		}
		else
		{
			$data['events'] = $this->admin_model->get_events($data['bgn'], $data['end'], $data['limit'], $data['from']);
			$data['cnt'] = $this->admin_model->get_events_count($data['bgn'], $data['end']);
		}
		
		if($succ != 0)
		{
			$data['success'] = $succ;
		}
		$this->show('admin/event_list', $hdata, $data);
	}*/
	
	//Új esemény.
	//Működés felfüggesztve 2017.07.21.
	/*public function event_new()
	{
		$this->load->library('form_validation');
		$hdata['title'] = 'Új esemény';
			
		$this->form_validation->set_rules('title', 'cím', 'trim|required|max_length[250]');
		$this->form_validation->set_rules('location', 'helyszín', 'trim|required|max_length[250]');
		$this->form_validation->set_rules('begin', 'kezdő dátum', 'required');
		$this->form_validation->set_rules('end', 'befejező dátum', 'required');
		$this->form_validation->set_rules('userfile', 'kép feltöltése', 'callback_image_upload');
		if ($this->form_validation->run() === FALSE) //sikertelen form validation
		{		
			if($this->input->post('save') != NULL) //a validation errorokhoz szükséges -> volt-e már adatmozgás
			{
				$data['wasdata'] = TRUE;
			}
			else
			{
				$data['wasdata'] = FALSE;
			}
			
			$this->show('admin/event_new', $hdata, $data);
		}
		else
		{
			//ha van kép, feltöltjük
			if (isset($_FILES['userfile']) && $_FILES['userfile']['name'] != '' && $this->upload->display_errors() == '')
			{
				$image_id = $this->admin_model->image_insert($this->upload->data()['file_name']);
			}
			else //nem volt kép vagy nem változott
			{
				$image_id = $this->input->post('userfile');
			}
			$success = $this->admin_model->event_insert($image_id);
			
			if($success == FALSE)
			{
				$data['wasdata'] = TRUE;
				$this->show('admin/event_new', $hdata, $data);
			}
			else
			{
				$this->event_list(0, 0, 0, $success);
			}
		}
	}
	
	//Esemény szerkesztése.
	public function event_edit($id)
	{
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('title', 'cím', 'trim|required|max_length[100]');
		$this->form_validation->set_rules('location', 'helyszín', 'trim|required|max_length[250]');
		$this->form_validation->set_rules('begin', 'kezdő dátum', 'required');
		$this->form_validation->set_rules('end', 'befejező dátum', 'required');
		$this->form_validation->set_rules('userfile', 'kép feltöltése', 'callback_image_upload');
		if ($this->form_validation->run() === TRUE) //sikeres form validation
		{
			//akarunk képet is feltölteni
			if (isset($_FILES['userfile']) && $_FILES['userfile']['name'] != '' && $this->upload->display_errors() == '')
			{
				$image_id = $this->admin_model->image_insert($this->upload->data()['file_name']);
			}
			else //nem volt kép vagy nem változott
			{
				$image_id = $this->input->post('userfile');
			}
			$data['success'] = $this->admin_model->event_update($id, $image_id);
		}
		else
		{
			$data['success'] = FALSE;
		}
		
		if($this->input->post('save') != NULL) //a validation errorokhoz szükséges -> volt-e már adatmozgás
		{
			$data['wasdata'] = TRUE;
			$data['event'] = $this->admin_model->get_event_by_id($id);
			$data['event']['id'] = $id;
			$data['event']['title'] = $this->input->post('title');
			$data['event']['location'] = $this->input->post('location');
			$data['event']['begin'] = $this->input->post('begin');
			$data['event']['end'] = $this->input->post('end');
			$data['event']['body'] = $this->input->post('body');
		}
		else
		{
			$data['wasdata'] = FALSE;
			$data['event'] = $this->admin_model->get_event_by_id($id);
		}
		
		$hdata['title'] = 'Esemény szerkesztése';
		$this->show('admin/event_edit', $hdata, $data);
	}
	
	//Esemény törlése.
	public function event_delete($id)
	{
		$this->admin_model->event_delete($id);
		redirect('admin/event_list');
	}*/
		
	//Form validation callback: legördülő menüből kötelező választani vizsgálat.
	function must_choose($selected, $nev = '')
	{
		if($selected != 'ures')
		{
			return TRUE;
		}
		$this->form_validation->set_message('must_choose', 'Kötelező választani a(z) '.$nev.' legördülő menüből!');
		return FALSE;
	}
	
	//Form validation callback: fájlfeltöltés.
	function image_upload()
	{
		if (isset($_FILES['userfile']) && $_FILES['userfile']['name'] != '') //van adat, ami fel akarunk tölteni
		{
			$config['upload_path'] = './uploads/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['max_width']  = '2500';
			$config['max_height']  = '2500';
			$config['max_filename'] = '100';
			$config['file_name'] = 'img_';
			$config['max_size'] = '2000';
			$config['allow_resize'] = TRUE;
			$this->load->library('upload', $config);
			if ( ! $this->upload->do_upload()) //ha nem sikerült a feltöltés.
			{
				$this->form_validation->set_message('image_upload', $this->upload->display_errors());
				return FALSE;
			}
		}
		return TRUE;
	}

	//érkezett-e a kép is a feltöltéshez (és sikerült-e)
	function image_upload_filename()
	{
		if (isset($_FILES['userfile']) && $_FILES['userfile']['name'] != '' && $this->upload->display_errors() == '')
			return $this->upload->data()['file_name'];
		else // nem volt kép vagy nem változott
			return $this->input->post('userfile');
	}
	
	//Form validation callback: egyediek-e a cikkek útvonalai
	function slug_unique($title, $slug)
	{
		$this->form_validation->set_message('slug_unique', 'Már létezik ilyen című cikk!');
		$res = $this->admin_model->existing_slug($slug);
		
		if($res === TRUE || $res === $this->input->post('id')) //egyedi vagy ez a sajátja
		{
			return TRUE;
		}
		return FALSE;
	}
	
	//Form validation callback: egyediek-a a statikus cikkek útvonalai
	function static_slug_unique($path)
	{
		$this->form_validation->set_message('static_slug_unique', 'Már létezik ilyen útvonalú statikus cikk!');
		$res = $this->admin_model->existing_static_slug($path);

		if($res === TRUE || $res === $this->input->post('id')) //egyedi vagy ez a sajátja
		{
			return TRUE;
		}
		return FALSE;
	}
}