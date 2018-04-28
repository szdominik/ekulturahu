<?php
//Statikus oldalak, illetve a kereséshez használandó modell
require_once('Base_model.php');
class Pages_model extends Base_Model {

	//Konstruktor
	public function __construct()
	{
		$this->load->database();
	}
	
	//A naptárban megjelenő adatokhoz szükséges információk
	public function get_events_for_calendar()
	{
		$this->db->select('id, title, begin, end')->from('event');
		$query = $this->db->get();
		return $query->result_array();
	}
	
	//Random idézet
	public function get_random_quote()
	{
		$rand = rand(1, $this->db->count_all_results('quotes'));
		$query = $this->db->get_where('quotes', array('id' => $rand));
		return $query->row();
	}
	
	//A napi évfordulóhoz szükséges adatok.
	public function get_calendar_datas()
	{
		$month = date('n');
		$day = date('j');
		$this->db->select('*')->from('calendar');
		$this->db->where(array('month' => $month, 'day' => $day));
		$this->db->order_by('year');
		$query = $this->db->get();
		return $query->result_array();
	}
}