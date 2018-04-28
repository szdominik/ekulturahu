<?php    
    //Tömb végéről leszed annyi elemet, hogy csak $limit darab elem maradjon benne.
	private function array_pop_to_limit($array, $limit)
	{
		if(count($array) > $limit)
		{
			$len = count($array) - $limit;
			for($i = 0; $i < $len; ++$i)
				array_pop($array);
		}
		return $array;
	}
	
	//Privát láthatóságú függvény a gyakran használt adatbázis lekérdezésekhez
	//a $filter a keresendő szövegrészt jelenti
	//csak a title alapján keres
	private function base_short_query($filter)
	{
		$this->db->select('articles.*, subcategory.name AS subcat_name, '
						. 'subcategory.slug AS subcat_slug, users.name AS user_name');
		$this->db->from('articles');
		$this->db->join('subcategory', 'subcategory.id = articles.subcategory_id', 'left');
		$this->db->join('users', 'users.id = articles.user_id', 'left');
		$this->db->where('articles.published', 1);
		$this->db->where('articles.pub_time <=', $this->datetimeNow());
		
		$filter_array = explode(' ', trim($filter));
		$like_part = '(';
		foreach($filter_array as $f)
			$like_part .= 'articles.title' . " LIKE '%" . $f . "%' AND ";
		$like_part = substr($like_part, 0, -5);
		$like_part .= ')';
		
		$this->db->where($like_part);
		$this->db->group_by('articles.id');
		$this->db->order_by('pub_time', 'DESC');
		
	}
	
	//Privát láthatóságú függvény a gyakran használt adatbázis lekérdezésekhez
	//a $filter a keresendő szövegrészt jelenti
	private function base_query($filter)
	{
		$this->db->select('articles.*, subcategory.name AS subcat_name, '
						. 'subcategory.slug AS subcat_slug, users.name AS user_name');
		$this->db->from('articles');
		$this->db->join('subcategory', 'subcategory.id = articles.subcategory_id', 'left');
		$this->db->join('users', 'users.id = articles.user_id', 'left');
		//$this->db->join('meta_value_article', 'meta_value_article.article_id = articles.id', 'left');
		//$this->db->join('metavalue', 'metavalue.id = meta_value_article.metavalue_id', 'left');
		$this->db->where('articles.published', 1);
		$this->db->where('articles.pub_time <=', $this->datetimeNow());
		
		$tables_for_filter = array('articles.title', 'articles.body', 'users.name');
								//'articles.eredeti_cim', 'articles.forgatokonyviro', 
								//'articles.producer', 'articles.operator');
								//'metavalue.name');
		$filter_array = explode(' ', trim($filter));
		
		$like_part = '(';
		foreach($tables_for_filter as $tff)
		{
			foreach($filter_array as $f)
				$like_part .= $tff . " LIKE '%" . $f . "%' AND ";
				
			$like_part = substr($like_part, 0, -5);
			$like_part .= ') OR (';
		}
		$like_part = substr($like_part, 0, -4);
		
		$this->db->where($like_part);
		$this->db->group_by('articles.id');
		$this->db->order_by('pub_time', 'DESC');
	}
	
	//Keresés egy szövegrész alapján (illetve lapozóadatokkal)
	//Azért ez még erősen átgondolandó. TODO.
	public function get_searched_data($filter, $limit, $from)
	{
		$this->base_short_query($filter);
		$query1 = $this->db->get();
		$q1_res_array = $query1->result_array();
		if($query1->num_rows() > $from)
		{
			//nincs limit, from: az elejéről a feleslegesek levétele
			for($i = 0; $i < $from; ++$i)
				array_shift($q1_res_array);
		}
		
		if(count($q1_res_array) < $limit)
		{
			$this->base_query($filter);
			$this->db->limit($limit - count($q1_res_array), max(array($from - $query1->num_rows(), 0)));
			$query2 = $this->db->get();
			if ($query2->num_rows() === 0)
				return array();
			
			if ($query1->num_rows() === 0)
				return $query2->result_array();
			else
			{
				$ids = array();
				$query = $query1->result_array();
				foreach($query as $q)
					$ids[] = $q['id'];

				foreach($query2->result_array() as $res)
					if(! in_array($res['id'], $ids))
						$query[] = $res;
				return $query;
			}
		}
		else
		{
			return $this->array_pop_to_limit($q1_res_array, $limit);
		}
	}
	
	//keresendő adatok száma //TODO: ezt ugye nem adjuk futtatjuk le minden lapon újra?
	public function get_searched_data_count($filter)
	{
		$this->base_query($filter);
		return $this->db->count_all_results();
	}
	
	//Keresés gyorsan, röviden (hehe), legördülő menübe
	//Azért ez még erősen átgondolandó. TODO.
	public function get_searched_data_short($filter, $limit)
	{
		$this->base_short_query($filter);
		$this->db->limit($limit);
		$query1 = $this->db->get();
		if($query1->num_rows() < $limit)
		{
			$this->base_query($filter); //nincs limit! Az id egyezés miatt lehetne végül $limitnél kisebb is.
			$query2 = $this->db->get();
			
			if ($query2->num_rows() === 0) //ha query2 nincs, akkor query1 sincs
				return array();
			if ($query1->num_rows() === 0)
				return $this->array_pop_to_limit($query2->result_array(), $limit);
			else
			{
				$ids = array();
				$query = $query1->result_array();
				foreach($query as $q)
					$ids[] = $q['id'];

				foreach($query2->result_array() as $res)
					if(! in_array($res['id'], $ids))
						$query[] = $res;
						
				return $this->array_pop_to_limit($query, $limit);
			}
		}
		else
		{
			return $query1->result_array();
		}
	}