<?php
//Szülőmodell
class Base_model extends CI_Model {

	//Konstruktor
	public function __construct()
	{
		$this->load->database();
	}
	
	//aktuális idő MySQL szintaxisú stringben
	public function datetimeNow()
	{
		$this->load->helper('date');
		$datestring = "%Y-%m-%d %H:%i:%s"; //MySQL szintaxisú string lesz majd a dátumból
		return mdate($datestring, now());
	}
	
	//aktuális dátum számjellegű stringként
	public function dateNow()
	{
		$this->load->helper('date');
		$datestring = "%Y%m%d";
		return mdate($datestring, now());
	}
	
	//Statisztikai adat beszúrása.
	public function statistics_insert($az, $ip)
	{
		$this->db->trans_start();
		
		$data = array(
			'cikk_egyedi_az' => $az,
			'IP' 			 => $ip,
			'date'			 => $this->dateNow()
		);
		$this->db->insert('statistics', $data);
		
		$this->db->trans_complete();
	}
	
	//Kategória-alkategória összekapcsolások.
	public function get_connections_by_categories()
	{
		$query = $this->db->get('sub_cat_connect');
		return $query->result_array();
	}
	
	//a kategóriák listája
	public function get_categories()
	{
		$query = $this->db->get('category');
		return $query->result_array();
	}
	
	//az alkategóriák listája
	public function get_subcategories()
	{
		$query = $this->db->get('subcategory');
		return $query->result_array();
	}
	
	//Egy útvonal alapján a kategória adatai
	public function get_categoryname_by_slug($slug)
	{
		if($slug == 'osszes')
		{
			return array(
				'id'	 => 0,
				'name'	 => 'Összes cikk',
				'slug'	 => 'osszes'
			);
		}
		
		$query = $this->db->get_where('category', array('slug' => $slug));
		return $query->row_array();
	}
	
	//Egy útvonal alapján az alkategória adatai
	public function get_subcategoryname_by_slug($slug)
	{
		$query = $this->db->get_where('subcategory', array('slug' => $slug));
		return $query->row_array();
	}
	
	//Egy id alapján a kategória adatai
	public function get_categoryname_by_id($id)
	{
		$query = $this->db->get_where('category', array('id' => $id));
		return $query->row_array();
	}
	
	//Egy id alapján az alkategória adatai
	public function get_subcategoryname_by_id($id)
	{
		$query = $this->db->get_where('subcategory', array('id' => $id));
		return $query->row_array();
	}
	
	//Statikus cikkek visszaadása
	public function get_statics()
	{
		$query = $this->db->get('static');
		return $query->result_array();
	}
	
	//Egy adott id alapján az esemény információinak visszaadása
	/*public function get_event_by_id($id)
	{
		$this->db->select('event.*, image.path AS image_name')->from('event');
		$this->db->join('image', 'image.id = event.image_id', 'left');
		$this->db->where('event.id', $id);
		$query = $this->db->get();
		return $query->row_array();
	}*/
	
	// Főoldalra kiteendő cikkek adatai
	public function get_articles_for_mainpage($from, $limit)
	{
		$this->db->select('articles.*, category.name AS cat_name, category.slug AS cat_slug, '
					. 'subcategory.name AS subcat_name, subcategory.slug AS subcat_slug, users.name AS user_name');
		$this->db->from('articles');
		$this->db->join('category', 'category.id = articles.category_id', 'left');
		$this->db->join('subcategory', 'subcategory.id = articles.subcategory_id', 'left');
		$this->db->join('users', 'users.id = articles.user_id', 'left');
		$this->db->where('articles.published', 1);
		$this->db->where('articles.mainpage', 1);
		$this->db->where('articles.pub_time <=', $this->datetimeNow());
		$this->db->order_by('articles.pub_time', 'DESC');
		$this->db->limit($limit, $from);
		
		$query = $this->db->get();
		return $query->result_array();
	}

	// Főoldalra kiteendő cikkek számossága
	public function get_articles_count_for_mainpage()
	{
		$this->db->from('articles');
		$this->db->where('articles.published', 1);
		$this->db->where('articles.mainpage', 1);
		$this->db->where('articles.pub_time <=', $this->datetimeNow());
		
		return $this->db->count_all_results();
	}

	//A cikkekhez kiválogatja a hozzájuk tartozó meta-kategóriát
	public function get_categories_metatype_for_articles($article_ids) {
		if (count($article_ids) !== 0) {
			$this->db->select('meta_value_article.article_id AS article_id, metavalue.name AS meta_name, metavalue.slug AS meta_slug');
			$this->db->from('metavalue');
			$this->db->join('meta_value_article', 'metavalue.id = meta_value_article.metavalue_id', 'left');
			$this->db->where('metavalue.type', 8);
			$this->db->where_in('meta_value_article.article_id', $article_ids);
			$query = $this->db->get();
			return $query->result_array();
		}
		return array();
	}
}