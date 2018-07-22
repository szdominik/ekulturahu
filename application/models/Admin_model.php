<?php
//Adminfelületen belüli lekérdezesek modellje
require_once('Base_model.php');
class Admin_model extends Base_Model {

	//Konstruktor
	public function __construct()
	{
		$this->load->database();
	}

	//Nullát ad vissza, ha null található benne.
	private function zero_if_needed($data)
	{
		if($data === NULL)
			return 0;
		return $data;
	}

	private function refactor_body($data)
	{
		if (strpos($data, '&lt;iframe') !== FALSE) {
			return str_replace(array('&lt;', '&gt;'), array('<', '>'), $data);
		}
		return $data;
	}
	
	//Egy cikk beszúrása
	public function article_insert($id_data)
	{
		$data = array(
			'title'				=> $this->input->post('title'),
			'slug'				=> $id_data['slug'],
			'category_id'		=> $this->input->post('category'),
			'subcategory_id'	=> $this->input->post('subcategory'),
			'published'			=> $this->zero_if_needed($this->input->post('published')),
			'pub_time'			=> $this->input->post('pub_time'),
			'mainpage'			=> $this->zero_if_needed($this->input->post('mainpage')),
			'comment'			=> $this->zero_if_needed($this->input->post('comment')),
			'login'				=> $this->zero_if_needed($this->input->post('login')),
			'user_id'			=> $this->input->post('user'),
			'kedv_vasar'		=> $this->input->post('kedv_vasar'),
			'eredeti_cim'		=> $this->input->post('eredeti_cim'),
			'ar'				=> $this->input->post('ar'),
			'terjedelem'		=> $this->input->post('terjedelem'),
			'forgatokonyviro'	=> $this->input->post('forgatokonyviro'),
			'operator'			=> $this->input->post('operator'),
			'producer'			=> $this->input->post('producer'),
			'body'				=> $this->refactor_body($this->input->post('body')),
			'image_horizontal'	=> $this->zero_if_needed($this->input->post('image_horizontal')),
			'image_path'		=> $id_data['image_path'],
		);
		
		$this->db->insert('articles', $data);
		return $this->db->insert_id();
	}
	
	//Egy cikk frissítése
	public function article_update($id, $id_data)
	{
			$data = array(
				'title'				=> $this->input->post('title'),
				'slug'				=> $id_data['slug'],
				'category_id'		=> $this->input->post('category'),
				'subcategory_id'	=> $this->input->post('subcategory'),
				'published'			=> $this->zero_if_needed($this->input->post('published')),
				'pub_time'			=> $this->input->post('pub_time'),
				'mainpage'			=> $this->zero_if_needed($this->input->post('mainpage')),
				'comment'			=> $this->zero_if_needed($this->input->post('comment')),
				'login'				=> $this->zero_if_needed($this->input->post('login')),
				'user_id'			=> $this->input->post('user'),
				'kedv_vasar'		=> $this->input->post('kedv_vasar'),
				'eredeti_cim'		=> $this->input->post('eredeti_cim'),
				'ar'				=> $this->input->post('ar'),
				'terjedelem'		=> $this->input->post('terjedelem'),
				'forgatokonyviro'	=> $this->input->post('forgatokonyviro'),
				'operator'			=> $this->input->post('operator'),
				'producer'			=> $this->input->post('producer'),
				'body'				=> $this->refactor_body($this->input->post('body')),
				'image_horizontal'	=> $this->zero_if_needed($this->input->post('image_horizontal')),
				'image_path'		=> $id_data['image_path'],
			);
			
			$this->db->where('id', $id);
			return $this->db->update('articles', $data);
	}
	
	//Egy cikk törlése
	public function article_delete($id)
	{
		$this->db->trans_start();
		//kép törlése - ha volt
		$this->db->select('image_path')->from('articles')->where('id', $id);
		$query1 = $this->db->get();
		$image_path = $query1->row_array()['image_path'];
		if($image_path != NULL)
		{
			//törlés a szerverről
			/*$this->db->select('path')->from('image')->where('id', $im_id);
			$query2 = $this->db->get();
			$path = $query2->row_array()['path'];*/
			unlink('uploads/'.$image_path);
			
			//törlés az adatbázisból
			//$this->db->delete('image', array('id' => $im_id));
		}
		//hozzátartozó hozzászólások törlése
		$this->db->delete('comments', array('article_id' => $id));
		//hozzátartozó metaadatok törlése
		$this->db->delete('meta_value_article', array('article_id' => $id));
		//végül a cikk törlése
		$this->db->delete('articles', array('id' => $id)); 
		$this->db->trans_complete();
	}
	
	//Egy kép beszúrása útvonal (név) alapján
	/*public function image_insert($path)
	{
		$data = array(
			'path' => $path,
		);
		$this->db->insert('image', $data);
		return $this->db->insert_id();
	}*/
	
	//Egy kép törlése
	public function image_delete($im_path, $attached_id, $what)
	{
		$this->db->trans_start();
		//törlés szerverről
		/*$this->db->select('path')->from('image')->where('id', $im_id);
		$query = $this->db->get();
		$path = $query->row_array()['path'];*/
		unlink('uploads/'.$im_path);
		
		//törlés az adatbázisból
		//$this->db->delete('image', array('id' => $im_id)); 
		
		if($what == 1) //eseménykép
		{
			//az id törlése az event táblából
			//$this->db->where('id', $attached_id);
			//$this->db->update('event', array('image_id' => '0'));
		}
		else //cikkép
		{
			//az id törlése a cikkből
			$this->db->where('id', $attached_id);
			$this->db->update('articles', array('image_path' => NULL));
		}
		$this->db->trans_complete();
	}
	
	//metaadat beszúrása
	public function metavalue_insert($data)
	{
		return $this->db->insert('metavalue', $data);
		//return $this->db->insert_id();
	}
	
	//metaadat frissítése
	public function metavalue_update($data)
	{
		$this->db->where('id', $data['id']);
		return $this->db->update('metavalue', $data);
	}
	
	//metaadat törlése
	public function metavalue_delete($id)
	{
		$this->db->trans_start();
		$this->db->delete('meta_value_article', array('metavalue_id' => $id)); 
		$this->db->delete('metavalue', array('id' => $id));
		$this->db->trans_complete();
	}
	
	//metaadat beszúrása cikkhez
	public function meta_value_article_insert($ac_id, $meta_id)
	{
		$data = array(
			'article_id'	=> $ac_id,
			'metavalue_id'	=> $meta_id
		);
		
		return $this->db->insert('meta_value_article', $data);
	}
	
	//metaadat törlése cikkből
	public function meta_value_article_delete($id)
	{
		$this->db->delete('meta_value_article', array('id' => $id)); 
	}
	
	//kategória-alkategória összekapcsolás beszúrás
	public function sub_cat_connect_insert($cat, $subcat)
	{
		$this->db->select('*')->from('sub_cat_connect')->where('category_id', $cat)->where('subcategory_id', $subcat);
		$query = $this->db->get();
		if ($query->num_rows === 0) //vagyis még nincs ilyen
		{
			$data = array(
				'category_id'   	=> $cat,
				'subcategory_id'	=> $subcat
			);
			
			$this->db->insert('sub_cat_connect', $data);
		}
	}
	
	//kategória-alkategória összekapcsolás törlés
	public function sub_cat_connect_delete($cat, $subcat)
	{
		$this->db->where('category_id', $cat);
		$this->db->where('subcategory_id', $subcat);
		$this->db->from('articles');
		$cnt = $this->db->count_all_results();
		if ($cnt === 0)
			$this->db->delete('sub_cat_connect', array('category_id' => $cat, 'subcategory_id' => $subcat)); 
	}
	
	//kategória beszúrása
	public function category_insert($type, $data)
	{
		switch ($type)
		{
			case '1': return $this->db->insert('category', $data); //kategória
			case '2': return $this->db->insert('subcategory', $data); //alkategória
			case '3': return $this->db->insert('metatype', $data); //metaadat-típus
		}
	}
	
	//kategória frissítése
	public function category_update($type, $data)
	{
		$this->db->where('id', $data['id']);
		switch ($type)
		{
			case '1': return $this->db->update('category', $data); //kategória
			case '2': return $this->db->update('subcategory', $data); //alkategória
			case '3': return $this->db->update('metatype', $data); //metaadat-típus
		}
	}
	
	//kategória törlése
	public function category_delete($type, $id)
	{
		if ($this->category_delete_ok($type, $id))
			switch ($type)
			{
				case '1': $this->db->delete('category', array('id' => $id)); break; //kategória
				case '2': $this->db->delete('subcategory', array('id' => $id)); break; //alkategória
				case '3': $this->db->delete('metatype', array('id' => $id)); break; //metaadat-típus
			}
	}
	
	//Törölhető-e az adott kategória.
	private function category_delete_ok($type, $id)
	{
		switch ($type)
		{
			case '1': //kategória
				$this->db->where('category_id', $id);
				$this->db->from('articles');
				break;
			case '2': //alkategória
				$this->db->where('subcategory_id', $id);
				$this->db->from('articles');
				break;
			case '3': //metaadat-típus
				$this->db->select('mva.article_id')->from('metatype');
				$this->db->join('metavalue', 'metavalue.type = metatype.id');
				$this->db->join('meta_value_article mva', 'mva.metavalue_id = metavalue.id');
				$this->db->where('metatype.id', $id);
				break;
		}
		$cnt = $this->db->count_all_results();
		return ($cnt > 0) ? FALSE : TRUE;
	}
	
	//felhasználói szint frissítése
	public function user_update_with_level($username, $level)
	{
		$this->db->where('username', $username);
		return $this->db->update('users', array('level' => $level));
	}
	
	//felhasználó törlése
	public function user_delete($id)
	{
		$this->db->delete('users', array('id' => $id)); 
	}
	
	//hozzászólás frissítése
	public function comment_update($id, $body)
	{
		$this->db->where('id', $id);
		return $this->db->update('comments', array('body' => $body));
	}
	
	//hozzászólás törlése
	public function comment_delete($id)
	{
		$this->db->delete('comments', array('id' => $id)); 
	}
	
	//statikus cikk beszúrása
	public function static_insert()
	{
		$data = array(
			'title'	=> $this->input->post('title'),
			'path'	=> $this->input->post('path'),
			'body'	=> $this->input->post('body'),
		);
		
		return $this->db->insert('static', $data);
	}
	
	//statikus cikk frissítése
	public function static_update($id)
	{
		$data = array(
			'title'	=> $this->input->post('title'),
			'path'	=> $this->input->post('path'),
			'body'	=> $this->input->post('body'),
		);
		
		$this->db->where('id', $id);
		return $this->db->update('static', $data);
	}

	//statikus cikk törlése
	public function static_delete($id)
	{
		$this->db->delete('static', array('id' => $id));
	}
	
	//esemény beszúrása (ha van, képpel)
	/*public function event_insert($im_id = 0)
	{
		$data = array(
			'title'		=> $this->input->post('title'),
			'user_id'	=> $this->session->userdata('id'),
			'location'	=> $this->input->post('location'),
			'begin'		=> $this->input->post('begin'),
			'end'		=> $this->input->post('end'),
			'body'		=> $this->input->post('body'),
		);
		
		if($im_id != 0)
		{
			$data['image_id'] = $im_id;
		}
		
		return $this->db->insert('event', $data);
	}*/
	
	//esemény frissítése
	/*public function event_update($id, $im_id = 0)
	{
		$data = array(
			'title'		=> $this->input->post('title'),
			'user_id'	=> $this->session->userdata('id'),
			'location'	=> $this->input->post('location'),
			'begin'		=> $this->input->post('begin'),
			'end'		=> $this->input->post('end'),
			'body'		=> $this->input->post('body'),
		);
		
		if($im_id != 0)
		{
			$data['image_id'] = $im_id;
		}
		
		$this->db->where('id', $id);
		return $this->db->update('event', $data);
	}*/
	
	//esemény törlése
	/*public function event_delete($id)
	{
		$this->db->trans_start();
		//kép törlése - ha volt
		$this->db->select('image_id')->from('event')->where('id', $id);
		$query1 = $this->db->get();
		$im_id = $query1->row_array()['image_id'];
		if($im_id != 0)
		{
			//törlés a szerverről
			$this->db->select('path')->from('image')->where('id', $im_id);
			$query2 = $this->db->get();
			$path = $query2->row_array()['path'];
			unlink('uploads/'.$path);
			
			//törlés az adatbázisból
			$this->db->delete('image', array('id' => $im_id)); 
		}
		//esemény törlése
		$this->db->delete('event', array('id' => $id));
		$this->db->trans_complete();
	}*/
	
	//kategória-alkategória összekapcsolásához szükséges adatok
	/*public function get_category_connections_datas($cat_id)
	{
		$this->db->select('c.id AS cid, sc.id AS scid, sc.name, count(ac.id) AS cnt')->from('category c');
		$this->db->join('sub_cat_connect scc', 'scc.category_id = c.id', 'left');
		$this->db->join('subcategory sc', 'scc.subcategory_id = sc.id', 'left');
		$this->db->join('articles ac', 'ac.category_id = c.id and ac.subcategory_id = sc.id', 'left');
		$this->db->where('c.id', $cat_id);
		$this->db->group_by('c.id');
		$this->db->group_by('sc.id');
		
		$query = $this->db->get();
		if ($query->num_rows() === 0)
		{
			return array();
		}
		return $query->result_array();
	}*/
	
	//cikkek szerkesztéséhez a cikkek adatainak lekérése
	public function get_articles_for_editlist($title, $category, $subcategory, $published_cat, $user, $limit, $from)
	{
		$this->db->select('articles.id, title, slug, users.name AS user_name, pub_time');
		$this->db->from('articles');
		$this->db->join('users', 'users.id = articles.user_id', 'left');
		if ($title != '0')
		{
			$this->db->like('title', $title);
		}
		if ($category != 0)
		{
			$this->db->where('category_id', $category);
		}
		if ($subcategory != 0)
		{
			$this->db->where('subcategory_id', $subcategory);
		}
		if ($user != 0)
		{
			$this->db->where('user_id', $user);
		}
		if($published_cat != 0)
		{
			if($published_cat == 1)
				$this->db->where('published', 1);
			else
				$this->db->where('published', 0);
		}
		$this->db->order_by('articles.pub_time', 'DESC');
		$this->db->limit($limit, $from);
		
		$query = $this->db->get();
		if ($query->num_rows() === 0)
		{
			return array();
		}
		return $query->result_array();
	}
	
	//cikkek szerkesztéséhez a cikkek számának lekérése
	public function get_articles_for_editlist_count($title, $category, $subcategory, $published_cat, $user, $limit, $from)
	{
		$this->db->select('articles.id, title, users.name AS user_name, pub_time');
		$this->db->from('articles');
		$this->db->join('users', 'users.id = articles.user_id', 'left');
		if ($title != '0')
		{
			$this->db->like('title', $title);
		}
		if ($category != 0)
		{
			$this->db->where('category_id', $category);
		}
		if ($subcategory != 0)
		{
			$this->db->where('subcategory_id', $subcategory);
		}
		if ($user != 0)
		{
			$this->db->where('user_id', $user);
		}
		if($published_cat != 0)
		{
			if($published_cat == 1)
				$this->db->where('published', 1);
			else
				$this->db->where('published', 0);
		}			
		return $this->db->count_all_results();
	}
	
	//egy id alapján a cikk adatainak lekérése
	public function get_article_by_id($id)
	{
		$this->db->select('articles.*')->from('articles');
		$this->db->where('articles.id', $id);
		
		$query = $this->db->get();
		return $query->row_array();
	}
	
	//metaadatok lekérése (lapozó-adatokkal, szűréssel típusra, névre)
	public function get_metas($limit = 50, $from = 0, $filter = 0, $meta_name = '0')
	{
		$this->db->select('metavalue.*, metatype.name AS type_name')->from('metavalue')->join('metatype', 'metatype.id = metavalue.type');
		if($filter != 0)
		{
			$this->db->where('metavalue.type', $filter);
		}
		if($meta_name != '0')
		{
			$this->db->like('metavalue.name', $meta_name);
		}
		$this->db->limit($limit, $from);
		$this->db->order_by('metatype.id');
		$this->db->order_by('metavalue.name');
		
		$query = $this->db->get();
		return $query->result_array();
	}
	
	//metaadatok számosságának lekérése
	public function get_metas_count($filter = 0, $meta_name = '0')
	{
		$this->db->select('metavalue.*, metatype.name AS type_name')->from('metavalue')->join('metatype', 'metatype.id = metavalue.type');
		if($filter != 0)
		{
			$this->db->where('metavalue.type', $filter);
		}
		if($meta_name != '0')
		{
			$this->db->like('metavalue.name', $meta_name);
		}
		return $this->db->count_all_results();
	}
	
	//lehetséges felhasználói szintek lekérése
	public function get_levels()
	{
		$query = $this->db->get('levels');
		return $query->result_array();
	}
	
	//metaadat típusok lekérése
	public function get_metatypes()
	{
		$this->db->select('id, name')->from('metatype');
		$query = $this->db->get();
		return $query->result_array();
	}
	
	//metatípusok lekérése a kategórialistába: hozzáadva a cikkek száma is
	public function get_metatypes_for_categorylist()
	{
		//$this->db->select('metatype.id, metatype.name, count(mva.article_id) AS cnt')->from('metatype');
		$this->db->select('metatype.id, metatype.name')->from('metatype');
		$this->db->join('metavalue', 'metavalue.type = metatype.id', 'left');
		//$this->db->join('meta_value_article mva', 'mva.metavalue_id = metavalue.id', 'left');
		$this->db->group_by('metatype.id');
		$query = $this->db->get();
		return $query->result_array();
	}
	
	//kategóriák  lekérése a kategórialistába: hozzáadva a cikkek száma is
	public function get_categories_for_categorylist()
	{
		$this->db->select('c.id, c.name, c.slug, count(articles.id) AS cnt')->from('category c');
		$this->db->join('articles', 'articles.category_id = c.id', 'left');
		$this->db->group_by('c.id');
		$query = $this->db->get();
		return $query->result_array();
	}
	
	//alkategóriák  lekérése a kategórialistába: hozzáadva a cikkek száma is
	public function get_subcategories_for_categorylist()
	{
		$this->db->select('sc.id, sc.name, sc.slug, count(articles.id) AS cnt')->from('subcategory sc');
		$this->db->join('articles', 'articles.subcategory_id = sc.id', 'left');
		$this->db->group_by('sc.id');
		$query = $this->db->get();
		return $query->result_array();
	}
	
	//metaadatok lekérése egy adott típushoz
	public function get_metas_by_type($type)
	{
		$this->db->select('id, name')->from('metavalue')->where('type', $type);
		if ($type == 4) { // kiadás éve
			$this->db->order_by('name', 'desc');
		} else {
			$this->db->order_by('name');
		}
		$query = $query = $this->db->get();
		return $query->result_array();
	}
	
	//egy adott cikkhez tartozó metaadatok lekérése
	public function get_metas_by_article($ac_id)
	{
		$this->db->select('meta_value_article.id AS meta_id, metatype.name AS type_name, metavalue.name AS name, '
							. 'metavalue.type AS type, metavalue.id AS metavalue_id');
		$this->db->from('meta_value_article');
		$this->db->join('metavalue', 'metavalue.id = meta_value_article.metavalue_id', 'left');
		$this->db->join('metatype', 'metatype.id = metavalue.type', 'left');
		$this->db->where('meta_value_article.article_id', $ac_id);
		$this->db->order_by('metavalue.type');
		$this->db->order_by('meta_value_article.id');
		
		$query = $query = $this->db->get();
		return $query->result_array();
	}
	
	//felhasználók lekérése (lapozással, szűréssel szintre)
	public function get_users($limit = 50, $from = 0, $level = 0, $name_filt = '0')
	{
		$this->db->select('users.*, levels.name AS level_name')->from('users')->join('levels', 'levels.id = users.level');
		if($level != 0)
		{
			$this->db->where('users.level', $level);
		}
		if($name_filt != '0')
		{
			$this->db->like('users.name', $name_filt);
		}
		$this->db->order_by('users.username');
		$this->db->limit($limit, $from);
		$query = $this->db->get();
		return $query->result_array();
	}
	
	//felhasználók számosságának lekérése
	public function get_users_count($level = 0, $name_filt = '')
	{
		$this->db->select('*')->from('users');
		if($level != 0)
		{
			$this->db->where('level', $level);
		}
		if($name_filt != '0')
		{
			$this->db->like('users.name', $name_filt);
		}
		return $this->db->count_all_results();
	}
	
	//a legalább hármas szintű (legalább cikkíró) felhasználók lekérése
	public function get_writer_users()
	{
		$this->db->select('id, name')->from('users')->where('level >=', 3);
		$this->db->order_by('users.name');
		$query = $this->db->get();
		return $query->result_array();
	}
	
	//hozzászólások lekérése (lapozással, szűréssel, szövegre és felhasználóra)
	public function get_comments($user = 0, $limit = 50, $from = 0, $filter = '')
	{
		$this->db->select('comments.*, users.name AS user_name, users.username AS user_username, '
						. 'articles.title, articles.slug, articles.pub_time, '
						. 'c.slug AS cslug, sc.slug AS scslug');
		$this->db->from('comments')->join('users', 'users.id = comments.user_id', 'left');
		$this->db->join('articles', 'comments.article_id = articles.id', 'left');
		$this->db->join('category c', 'c.id = articles.category_id', 'left');
		$this->db->join('subcategory sc', 'sc.id = articles.subcategory_id', 'left');
		if($user != 0)
		{
			$this->db->where('comments.user_id', $user);
		}
		if($filter != '')
		{
			$this->db->like('body', $filter);
		}
		$this->db->order_by('comments.date', 'DESC');
		$this->db->limit($limit, $from);
		$query = $this->db->get();
		return $query->result_array();
	}
	
	//hozzászólások számának lekérése
	public function get_comments_count($user = 0, $filter = '')
	{
		$this->db->select('comments.*, users.name AS user_name, users.username AS user_username');
		$this->db->from('comments')->join('users', 'users.id = comments.user_id', 'left');
		if($user != 0)
		{
			$this->db->where('comments.user_id', $user);
		}
		$this->db->like('body', $filter);
		return $this->db->count_all_results();
	}
	
	//események lekérése (lapozással, szűréssel felhasználóra vagy dátumokra)
	/*public function get_events($bgn, $end, $limit = 50, $from = 0, $uid = 0)
	{
		$this->db->select('*')->from('event');
		if($bgn != '0' && $bgn != '')
		{
			$this->db->where('begin >=', $bgn);
		}
		if($end != '0' && $end != '')
		{
			$this->db->where('end <=', $end);
		}
		if($uid != 0)
		{
			$this->db->where('user_id', $uid);
		}
		$this->db->order_by('end', 'DESC');
		$this->db->limit($limit, $from);
		
		$query = $this->db->get();
		return $query->result_array();
	}
	
	//események számosságának lekérése
	public function get_events_count($bgn, $end, $uid = 0)
	{
		$this->db->select('*')->from('event');
		if($bgn != '0')
		{
			$this->db->where('begin >=', $bgn);
		}
		if($end != '0')
		{
			$this->db->where('end <=', $end);
		}
		if($uid != 0)
		{
			$this->db->where('user_id', $uid);
		}
		return $this->db->count_all_results();
	}*/
	
	//egy statikus cikk lekérése id alapján
	public function get_static_by_id($id)
	{
		$this->db->where('id', $id);
		$query = $this->db->get('static');
		return $query->row_array();
	}
	
	//egy metaadat  lekérése id alapján
	public function get_meta_by_id($id)
	{
		$this->db->select('*')->from('metavalue')->where('id', $id);
		$query = $this->db->get();
		if ($query->num_rows() === 0)
		{
			return FALSE;
		}
		return $query->row_array();
	}
	
	//egy metaadat lekérése útvonal és típus alapján
	public function get_meta_by_slug_and_type($slug, $type)
	{
		$this->db->select('*')->from('metavalue')->where('type', $type)->where('slug', $slug);
		$query = $this->db->get();
		if ($query->num_rows() === 0)
		{
			return FALSE;
		}
		return $query->row_array();
	}
	
	//egy felhasználó lekérése id alapján
	public function get_user_by_id($id)
	{
		$this->db->where('id', $id);
		$query = $this->db->get('users');
		return $query->row_array();
	}
	
	//egy hozzászólás  lekérése id alapján
	public function get_comment_by_id($id)
	{
		$this->db->select('comments.*, users.name AS user_name, users.username AS user_username');
		$this->db->from('comments')->join('users', 'users.id = comments.user_id', 'left');
		$this->db->where('comments.id', $id);
		$query = $this->db->get();
		return $query->row_array();
	}
	
	//annak vizsgálata, hogy létezik-e már ilyen útvonalú cikk
	public function existing_slug($slug)
	{
		$this->db->where('slug', $slug);
		$query = $this->db->get('articles');

		if($query->num_rows() > 0) //vagyis ==1
		{
			return $query->row_array()['id'];
		}
		return TRUE;
	}
	
	//annak vizsgálata, hogy létezik-e már ilyen útvonalú statikus cikk
	public function existing_static_slug($slug)
	{
		$this->db->where('path', $slug);
		$query = $this->db->get('static');

		if($query->num_rows() > 0) //vagyis ==1
		{
			return $query->row_array()['id'];
		}
		return TRUE;
	}
}