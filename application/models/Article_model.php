<?php
//A cikkekk kapcsolatos lekérdezések modellje
require_once('Base_model.php');
class Article_model extends Base_Model {

	//Konstruktor
	public function __construct()
	{
		$this->load->database();
	}
	
	//Egy útvonal alapján a statikus cikk adatainak visszaadása
	public function get_info_from_static($page)
	{
		$this->db->where('path', $page);
		$query = $this->db->get('static');
		return $query->row_array();
	}
	
	//Egy cikk adatainak visszaadása
	public function get_article($slug = '')
	{
		$this->db->select('articles.*, subcategory.name AS subcat_name, subcategory.slug AS subcat_slug, '
						. 'category.name AS cat_name, category.slug AS cat_slug, users.name AS user_name');
		$this->db->from('articles');
		$this->db->join('category', 'category.id = articles.category_id', 'left');
		$this->db->join('subcategory', 'subcategory.id = articles.subcategory_id', 'left');
		$this->db->join('users', 'users.id = articles.user_id', 'left');
		$this->db->where('articles.slug', $slug);
		$query = $this->db->get();
		return $query->row_array();
	}
	
	//Hozzászólás beszúrása
	public function comment_insert($data)
	{
		return $this->db->insert('comments', $data);
	}
	
	//Hozzászólások lekérése
	public function get_comments($id = 0)
	{
		$this->db->select('comments.*, users.name AS user_name, users.username AS users_username');
		$this->db->from('comments')->join('users', 'users.id = comments.user_id', 'left');
		$this->db->where('article_id', $id)->order_by('id', 'asc');
		$query = $this->db->get();
		return $query->result_array();
	}
	
	//Privát láthatóságú függvény a gyakran használt adatbázis lekérdezések "kezdetére"
	//Amennyiben adott a $meta_id, akkor szűrjük csak azokra a cikkekre, amelyekhez van társítva ilyen metaadat
	private function query_start($meta_id = '')
	{
		$this->db->select('articles.*, users.name AS user_name, '
					. 'subcategory.name AS subcat_name, subcategory.slug AS subcat_slug, subcategory.id AS subcat_id, '
					. 'category.name AS cat_name, category.slug AS cat_slug');
		$this->db->from('articles');
		$this->db->join('category', 'category.id = articles.category_id', 'left');
		$this->db->join('subcategory', 'subcategory.id = articles.subcategory_id', 'left');
		$this->db->join('users', 'users.id = articles.user_id', 'left');
		if($meta_id != '')
		{
			$this->db->join('meta_value_article', 'meta_value_article.article_id = articles.id');
			$this->db->where('meta_value_article.metavalue_id', $meta_id);
		}
		$this->db->where('articles.published', 1);
		$this->db->where('articles.pub_time <=', $this->datetimeNow());
	}
	
	//Privát láthatóságú függvény a gyakran használt adatbázis lekérdezések "végére"
	private function query_end($from = 0, $limit = 1)
	{
		$this->db->order_by('pub_time', 'DESC');
		$this->db->limit($limit, $from);
	}

	//Kategória alapján cikkek lekérése
	public function get_articles_by_category($from, $limit, $cat)
	{
		$this->query_start();
		if ($cat != 'osszes') {
			$this->db->where('category.slug', $cat);
		}
		$this->query_end($from, $limit);
		$query = $this->db->get();
		if ($query->num_rows() === 0)
		{
			return array();
		}
		return $query->result_array();
	}
	
	//Kategóriák alapján a cikkek számának lekérése
	public function get_articles_count_by_category($cat)
	{
		$this->query_start();
		if ($cat != 'osszes') {
			$this->db->where('category.slug', $cat);
		}		
		//Nincs query-end
		return $this->db->count_all_results();
	}
	 
	//(Al)kategória alapján cikkek lekérése
	public function get_articles_by_subcategory($from, $limit, $subcat)
	{
		$this->query_start();
		$this->db->where('subcategory.slug', $subcat);
		$this->query_end($from, $limit);
		$query = $this->db->get();
		if ($query->num_rows() === 0)
		{
			return array();
		}
		return $query->result_array();
	}
	
	//(Al)kategória alapján a cikkek számának lekérése
	public function get_articles_count_by_subcategory($subcat)
	{
		$this->query_start();
		$this->db->where('subcategory.slug', $subcat);			
		//Nincs query-end
		return $this->db->count_all_results();
	}
	
	//Egy adott cikk metaadatainak lekérése
	public function get_metas_by_article($ac_id)
	{
		$this->db->select('metatype.name AS type_name, metavalue.name AS meta_name, metavalue.slug AS slug, metavalue.id AS meta_id, '
							. 'metatype.id AS type_id, metatype.slug AS type_slug');
		$this->db->from('meta_value_article');
		$this->db->join('metavalue', 'metavalue.id = meta_value_article.metavalue_id', 'left');
		$this->db->join('metatype', 'metatype.id = metavalue.type', 'left');
		$this->db->where('meta_value_article.article_id', $ac_id);
		$this->db->order_by('metatype.id');
		$query = $query = $this->db->get();
		return $query->result_array();
	}
	
	//Kapcsolódó tartalmak lekérdezése - metaid alapján
	public function get_other_articles_by_meta_id($meta_id)
	{
		$this->query_start($meta_id);
		$this->db->order_by('pub_time', 'DESC');
		
		$query = $this->db->get();
		if ($query->num_rows() === 0)
		{
			return array();
		}
		return $query->result_array();
	}
	
	//Egy útvonal alapján az adott címke adatinak lekérése (ha van ilyen)
	public function get_meta_by_slug($type_slug, $slug)
	{
		$this->db->select('metavalue.name AS name, metavalue.slug AS slug, metavalue.id AS id, metatype.name AS type_name, metatype.slug AS type_slug');
		$this->db->from('metavalue');
		$this->db->join('metatype', 'metatype.id = metavalue.type', 'left');
		$this->db->where('metatype.slug', $type_slug);
		$this->db->where('metavalue.slug', $slug);
		$query = $this->db->get();
		if ($query->num_rows() === 0)
		{
			return FALSE;
		}
		return $query->row_array();
	}
	
	//Egy adott metaadathoz tartozó cikkek lekérése, lapozóadatokkal
	public function get_articles_by_meta($meta_id, $limit = 20, $from = 0)
	{
		$this->query_start($meta_id);
		$this->query_end($from, $limit);
		
		$query = $this->db->get();
		if ($query->num_rows() === 0)
		{
			return array();
		}
		return $query->result_array();
	}
	
	//Egy adott metaadathoz tartozó cikkek számának lekérése
	public function get_articles_by_meta_count($meta_id)
	{
		$this->query_start($meta_id);
		return $this->db->count_all_results();
	}
	
	//Egy adott cikkszerzőhöz tartozó cikkek lekérése.
	public function get_articles_by_author($name, $from, $limit)
	{
		$this->query_start();
		$this->db->where('users.name', $name);
		$this->query_end($from, $limit);
		$query = $this->db->get();
		if ($query->num_rows() === 0)
		{
			return array();
		}
		return $query->result_array();
	}
	
	//Egy adott cikkszerzőhöz tartozó cikkek számának lekérése.
	public function get_articles_count_by_author($name)
	{
		$this->query_start();
		$this->db->where('users.name', $name);
		return $this->db->count_all_results();
	}

	private function title_query($filter)
	{
		$this->db->select('articles.*, subcategory.name AS subcat_name, '
						. 'subcategory.slug AS subcat_slug, users.name AS user_name');
		$this->db->from('articles');
		$this->db->join('subcategory', 'subcategory.id = articles.subcategory_id', 'left');
		$this->db->join('users', 'users.id = articles.user_id', 'left');
		$this->db->where('articles.published', 1);
		$this->db->where('articles.pub_time <=', $this->datetimeNow());
		
		$filter_array = explode(' ', trim($filter));
		$like_part = 'MATCH(articles.title) AGAINST(';
		foreach($filter_array as $f)
			$like_part .= "'+*" . $f . "*' ";
		$like_part .= ' IN BOOLEAN MODE)';
		
		$this->db->where($like_part);
		$this->db->group_by('articles.id');
		$this->db->order_by('pub_time', 'DESC');
	}

	private function search_query($filter, $earlier_results)
	{
		$this->db->select('articles.*, subcategory.name AS subcat_name, '
						. 'subcategory.slug AS subcat_slug, users.name AS user_name');
		$this->db->from('articles');
		$this->db->join('subcategory', 'subcategory.id = articles.subcategory_id', 'left');
		$this->db->join('users', 'users.id = articles.user_id', 'left');
		$this->db->where('articles.published', 1);
		$this->db->where('articles.pub_time <=', $this->datetimeNow());
		
		if(count($earlier_results) > 0) {
			$id_part = 'articles.id NOT IN (';
			foreach($earlier_results as $row)
				$id_part .= $row['id'] . ', ';
			$id_part = substr($id_part, 0, -2);
			$id_part .= ')';
			$this->db->where($id_part);
		}

		$tables_for_filter = array('articles.body', 'users.name');
		$filter_array = explode(' ', trim($filter));

		$like_part = ' ( ';
		foreach($tables_for_filter as $tff)
		{
			$like_part .= "MATCH(" . $tff . ") AGAINST('";
			foreach($filter_array as $f)
				$like_part .= "+*" . $f . "* ";
			$like_part .= "' IN BOOLEAN MODE)";
			$like_part .= ' OR ';
		}
		$like_part = substr($like_part, 0, -4);
		$like_part .= ')';
		
		$this->db->where($like_part);
		$this->db->group_by('articles.id');
		$this->db->order_by('pub_time', 'DESC');
	}

	private function search_metavalue($filter, $earlier_results)
	{
		$this->db->select('article_id');
		$this->db->from('meta_value_article');
		$this->db->join('metavalue', 'metavalue.id = meta_value_article.metavalue_id', 'left');

		if(count($earlier_results) > 0) {
			$id_part = 'meta_value_article.article_id NOT IN (';
			foreach($earlier_results as $row)
				$id_part .= $row['id'] . ', ';
			$id_part = substr($id_part, 0, -2);
			$id_part .= ')';
			$this->db->where($id_part);
		}

		$filter_array = explode(' ', trim($filter));
		$like_part = 'MATCH(metavalue.name) AGAINST(';
		foreach($filter_array as $f)
			$like_part .= "'+*" . $f . "*' ";
		$like_part .= ' IN BOOLEAN MODE)';

		$this->db->where($like_part);
		$ids = $this->db->get()->result_array();
		
		if(count($ids) > 0) {
			$this->db->select('articles.*, subcategory.name AS subcat_name, '
							. 'subcategory.slug AS subcat_slug, users.name AS user_name');
			$this->db->from('articles');
			$this->db->join('subcategory', 'subcategory.id = articles.subcategory_id', 'left');
			$this->db->join('users', 'users.id = articles.user_id', 'left');
			$this->db->where('articles.published', 1);
			$this->db->where('articles.pub_time <=', $this->datetimeNow());

			$id_part = 'articles.id IN (';
			foreach($ids as $id)
				$id_part .= $id['article_id'] . ', ';
			$id_part = substr($id_part, 0, -2);
			$id_part .= ')';

			$this->db->where($id_part);
			$this->db->group_by('articles.id');
			$this->db->order_by('pub_time', 'DESC');

			return $this->db->get()->result_array();
		}
		else
			return array();
	}

	private function array_fit_and_sort($filter, $array, $limit, $from)
	{
		if(count($array) > $from)
			for($i = 0; $i < $from; ++$i)
				array_shift($array);

		$new_array = array();
		if(count($array) > $limit)
			for($i = 0; $i < $limit; ++$i)
				$new_array[] = $array[$i]; 
		else
			$new_array = $array;

		// -1 == a / 1 == b
		uasort($new_array, function($a, $b) use($filter) {
			$filters = explode(' ', trim(strtolower($filter)));
			$a_contains = true;
			$b_contains = true;
			foreach($filters as $f) {
				if (strpos(strtolower($a['title']), $f) === FALSE)
					$a_contains = false;
				if (strpos(strtolower($b['title']), $f) === FALSE)
					$b_contains = false;
			}

			if ($a_contains && !$b_contains) {
				return -1;
			} elseif (!$a_contains && $b_contains) {
				return 1;
			}
			return ($a['pub_time'] < $b['pub_time']) ? 1 : -1;
		});
		return $new_array;
	}

	public function get_searched_data($filter, $limit, $from, &$cnt)
	{
		$this->title_query($filter);
		$results = $this->db->get()->result_array();
		
		$this->search_query($filter, $results);
		$results = array_merge($results, $this->db->get()->result_array());
		
		$meta_results = $this->search_metavalue($filter, $results);
		$results = array_merge($results, $meta_results);

		$cnt = count($results);

		return $this->array_fit_and_sort($filter, $results, $limit, $from);
	}

	public function get_searched_data_short($filter, $limit)
	{
		$this->title_query($filter);
		$this->db->limit($limit);
		$query = $this->db->get();

		return $query->result_array();
	}
}