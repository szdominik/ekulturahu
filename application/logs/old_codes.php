<?php    
    //Tömb végéről leszed annyi elemet, hogy csak $limit darab elem maradjon benne.
	function array_pop_to_limit($array, $limit)
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
	function base_short_query($filter)
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
	function base_query($filter)
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
	function get_searched_data($filter, $limit, $from)
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
	function get_searched_data_count($filter)
	{
		$this->base_query($filter);
		return $this->db->count_all_results();
	}
	
	//Keresés gyorsan, röviden (hehe), legördülő menübe
	//Azért ez még erősen átgondolandó. TODO.
	function get_searched_data_short($filter, $limit)
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


	<?php if(! empty($comments)): //kommentek megjelenítése
		foreach($comments as $c): ?>
			<div class="row">
				<div class="col-md-5">
					<div class="panel panel-default">
						<div class="panel-heading">
							<?php echo $c['date'] . ' &ndash; írta: ';
								if($c['user_name'] != NULL): 
									echo $c['user_name'];
								elseif($c['users_username'] != NULL):
									echo $c['users_username'];
								else:
									echo 'törölt felhasználó';
								endif;
								if($this->session->userdata('logged_in') == TRUE && $this->session->userdata('id') === $c['user_id'])
									echo ' (' . anchor("admin/comment_list", "szerkesztés / törlés") . ')';
							?>
						</div>
						<div class="panel-body">
							<?php echo $c['body']; ?>
						</div>
					</div>
				</div>
			</div>
<?php endforeach; endif; ?>

<?php 
if(FALSE && $this->session->userdata('logged_in') === TRUE && $ac_item['comment'] != 0): //bejelentkezett felhasználó írhat kommentet
	echo form_open(current_url());
?>

		<?php if (isset($success) && $success == FALSE): ?>
			<div class="row">
				<div class="col-md-5">
					<div class="alert alert-dismissible alert-danger" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						Sikertelen hozzászólás!
						<?php echo validation_errors();	?>
					</div>
				</div>
			</div>
		<?php endif; ?>
		
		<div class="row">
			<div class="col-md-5">
				<div class="form-group">
					<p class="form-control-static">
						Hozzászólás (
						<?php if($this->session->userdata('name') != ''):
							echo $this->session->userdata('name');
						else: 
							echo $this->session->userdata('username');
						endif; ?>
						)
					</p>
					<?php $data = array(
								  'name'        => 'comment',
								  'value'       => '',
								  'maxlength'   => '5000',
								  'rows'		=> '3',
								  'class'		=> 'form-control',
								);
						echo form_textarea($data);?>
				</div>
			</div>
		</div>
		<button type="submit" value="comment_send" name="save" class="btn btn-default">Mentés</button>
	</form>
<?php endif; ?>
