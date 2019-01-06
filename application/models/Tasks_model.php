<?php
// Különböző alkalmanként vagy időzítve végrehajtandó backend-feladatok mögötti logika
require_once('Base_model.php');
class Tasks_model extends Base_Model {

	//Konstruktor
	public function __construct()
	{
		$this->load->database();
		$GLOBALS['article_num'] = 18000;
		$GLOBALS['article_limit'] = 2000;
	}

	// Ha ezt módosítod, van a Base controllerben is egy.
	private function generate_link($article)
	{
		$link = substr($article['pub_time'], 0, 4) . 
				'/' . substr($article['pub_time'], 5, 2) . '/' . substr($article['pub_time'], 8, 2) . 
				'/' . $article['slug'];
		return $link;
	}
	
	//régi linkeket az adatbázisból eltakarító szkript
	public function remove_mutat_php_links()
	{
		$from = 0;
		$count = 0;
		while($from < $GLOBALS['article_num']) {
			$this->db->select('id, title, body');
			$this->db->limit($GLOBALS['article_limit'], $from);
			$cikk = $this->db->get('articles');

			foreach($cikk->result_array() as $a)
			{
				if(strpos($a['body'], 'mutat.php?') !== FALSE) {
					$ab = str_replace('mutat.php?cid=', 'qqq/', $a['body']); // or mutat.php?id=
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
								echo 'hiba!' . $a['id'] . "<br />";
								continue;
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
						echo $a['id'] . " - " . $a['title'] . "<br />";
						++$count;
						$data = array('body' => $newarr);
						$this->db->where('id', $a['id']);
						$this->db->update('articles', $data);
					}
				}
			}
			$from += $GLOBALS['article_limit'];
		}
		echo 'DONE: ' . $count . ' article updated.';
	}

	public function cikkek_kepei_replace() {
		$from = 0;
		$count = 0;
		while($from < $GLOBALS['article_num']) {
			$this->db->select('id, title, body');
			$this->db->limit($GLOBALS['article_limit'], $from);
			$cikk = $this->db->get('articles');

			foreach($cikk->result_array() as $a)
			{ 
				if(strpos($a['body'], '/cikkek_kepei/user_feltoltesek/') !== FALSE) {
					$newBody = str_replace('/cikkek_kepei/user_feltoltesek/', base_url('/uploads/articles') . '/', $a['body']);

					echo $a['id'] . " - " . $a['title'] . "<br />";
					++$count;
					$data = array('body' => $newBody);
					$this->db->where('id', $a['id']);
					$this->db->update('articles', $data);
				}
			}
			$from += $GLOBALS['article_limit'];
		}
		echo 'DONE: ' . $count . ' article updated.';
	}

	public function refactor_article_body_formatting() {
		$from = 0;
		$count = 0;
		while($from < $GLOBALS['article_num']) {
			$this->db->select('id, title, body');
			$this->db->limit($GLOBALS['article_limit'], $from);
			$cikk = $this->db->get('articles');
	
			foreach($cikk->result_array() as $a)
			{
				$newbody = str_replace(
					array('<div', 'div>', ' class="western"', ' align="justify"', ' align="JUSTIFY"', ' style="text-align: justify;"', ' style="text-align: justify"'),
					array('<p', 'p>', '', '', '', '', ''),
					$a['body']
				);
	
				if($newbody !== $a['body']) {
					echo $a['id'] . " - " . $a['title'] . "<br />";
					++$count;
					$data = array('body' => $newbody);
					$this->db->where('id', $a['id']);
					$this->db->update('articles', $data);
				}
			}
			$from += $GLOBALS['article_limit'];
		}
		echo 'DONE: ' . $count . ' article updated.';
	}

	public function generate_sitemap_xml() {
		$from = 0;

		$content = '<?xml version="1.0" encoding="UTF-8"?>';
		$content .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

		$static_articles = $this->get_statics();
		foreach ($static_articles as $st) {
			$content .= '<url>';
			$content .= '<loc>' . site_url($st['path']) . '</loc>';
			$content .= '</url>';
		}

		while($from < $GLOBALS['article_num']) {
			$this->db->select('slug, pub_time');
			$this->db->where('articles.published', 1);
			$this->db->where('articles.pub_time <=', $this->datetimeNow());
			$this->db->limit($GLOBALS['article_limit'], $from);
			$cikk = $this->db->get('articles');

			foreach($cikk->result_array() as $a)
			{
				$content .= '<url>';
				$content .= '<loc>' . site_url($this->generate_link($a)) . '</loc>';
				$content .= '</url>';
			}

			$from += $GLOBALS['article_limit'];
		}
		$content .= '</urlset>';

		$xml_file = fopen('sitemap.xml', 'w') or die("Unable to open file!");
		fwrite($xml_file, $content);
		fclose($xml_file);
		echo 'DONE: <a href="/sitemap.xml">sitemap.xml</a>';
	}
}