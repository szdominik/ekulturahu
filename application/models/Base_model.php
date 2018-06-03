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
	
	//Főoldalra kiteendő cikkek adatai
	public function get_articles_for_mainpage()
	{
		$this->db->select('articles.*, category.name AS cat_name, category.slug AS cat_slug, '
					. 'subcategory.name AS subcat_name, subcategory.slug AS subcat_slug, users.name AS user_name');
		$this->db->from('articles');
		$this->db->join('category', 'category.id = articles.category_id', 'left');
		$this->db->join('subcategory', 'subcategory.id = articles.subcategory_id', 'left');
		$this->db->join('users', 'users.id = articles.user_id', 'left');
		$this->db->where('articles.published', 1);
		$this->db->where('articles.mainpage', 1);
		$this->db->where('articles.image_path != ', NULL);
		$this->db->where('articles.pub_time <=', $this->datetimeNow());
		$this->db->order_by('articles.pub_time', 'DESC');
		$this->db->limit(16);
		
		$query = $this->db->get();
		return $query->result_array();
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

	// Ha ezt módosítod, van a Base controllerben is egy.
	protected function generate_link($article)
	{
		$link = substr($article['pub_time'], 0, 4) . 
				'/' . substr($article['pub_time'], 5, 2) . '/' . substr($article['pub_time'], 8, 2) . 
				'/' . $article['slug'];
		return $link;
	}
	
	//régi linkeket az adatbázisból eltakarító szkript
	public function regi_linkek_szkript()
	{
		$from = 0;
		$limit = 2000;
		while($from < 18000) {
			$this->db->select('id, title, body');
			$this->db->limit($limit, $from);
			$cikk = $this->db->get('articles');

			foreach($cikk->result_array() as $a)
			{
				if(strpos($a['body'], 'mutat.php?') !== FALSE) {
					$ab = str_replace('mutat.php?cid=', 'qqq/', $a['body']);
					$arr = str_split($ab);
					$newarr = '';
					$volt = FALSE;

					for($i = 0; $i < count($arr); $i++)
					{
						if($arr[$i] == 'q' && $arr[$i+1] == 'q' && $arr[$i+2] == 'q' && $arr[$i+3] == '/')
						{
							$j = $i+4;
							$szamok = array();
							while($arr[$j] != '"')
							{
								$szamok[] = $arr[$j];
								$j++;
							}
							$szam = join('', $szamok);
							
							$this->db->select('art.slug, art.pub_time')->from('articles art');
							$this->db->where('art.id', $szam);
							$query = $this->db->get()->row_array();
							if(empty($query))
							{
								echo 'hiba!' . $a['id'] . "\n";
							}
							
							$link = $this->generate_link($query);
							$newarr = $newarr . $link;
							$i = $j - 1;
							$volt = TRUE;
						}
						else
						{
							$newarr = $newarr . $arr[$i];
						}
					}
					
					if($volt === TRUE) {
						echo $a['id'] . " - " . $a['title'] . "\n";
						$data = array('body' => $newarr);
						$this->db->where('id', $a['id']);
						$this->db->update('articles', $data);
					}
				}
			}
			$from += $limit;
		}
	}

	public function cikkek_kepei_replace() {
		$from = 0;
		$limit = 2000;
		while($from < 18000) {
			$this->db->select('id, title, body');
			$this->db->limit($limit, $from);
			$cikk = $this->db->get('articles');

			foreach($cikk->result_array() as $a)
			{ 
				if(strpos($a['body'], '/cikkek_kepei/user_feltoltesek/') !== FALSE) {
					$newBody = str_replace('/cikkek_kepei/user_feltoltesek/', base_url('/uploads/articles') . '/', $a['body']);

					//echo $a['id'] . " - " . $a['title'] . "\n";
					$data = array('body' => $newBody);
					$this->db->where('id', $a['id']);
					$this->db->update('articles', $data);
				}
			}
			$from += $limit;
		}
		echo "kesz!";
	}

	public function div_replace() {
    	$this->db->select('id, title, body');
		$cikk = $this->db->get('articles');

		foreach($cikk->result_array() as $a)
		{
			$newbody = str_replace(array('<div', 'div>'), array('<p', 'p>'), $a['body']);

			if(strpos($a['body'], '<div') !== FALSE) {
				// echo $a['id'] . " - " . $a['title'] . "\n";
				$data = array('body' => $newbody);
				$this->db->where('id', $a['id']);
				$this->db->update('articles', $data);
			}
		}
	}
}