<?php
//A statikus oldalakkal, illetve a kereséssel kapcsolatos műveletek vezérlője
require_once('Base.php');
class Pages extends Base {

	//Konstruktor.
	public function __construct()
	{
		parent::__construct();
		$this->load->model('pages_model');
	}
	
	//random függvény tesztelési célokból
	public function test()
	{
		// $this->pages_model->regi_linkek_szkript();
		// $this->pages_model->refactor_article_body();
		// $this->pages_model->cikkek_kepei_replace();
	}
	
	//Esménynaptár.
	public function events()
	{
		$hdata['title'] = 'Eseménynaptár';
		$data['events'] = $this->pages_model->get_events_for_calendar();
		$this->show('pages/events', $hdata, $data);
	}
	
	//Egy esemény lekérése (AJAX GET érkezik).
	public function get_event($id)
	{
		$event = $this->pages_model->get_event_by_id($id);
		ob_clean();
		echo json_encode($event);
	}
	
	//Napi évfordulók.
	public function calendar()
	{
		$hdata['title'] = 'Napi évfordulók';
		$data['quote'] = $this->pages_model->get_random_quote();
		
		$datas = $this->pages_model->get_calendar_datas();
		$data['birth'] = array();
		$data['death'] = array();
		$data['else'] = array();
		foreach($datas as $d)
		{
			switch($d['type'])
			{
				case '0': $data['birth'][] = $d; break;
				case '1': $data['death'][] = $d; break;
				default: $data['else'][] = $d; break; //elvileg 2-es, de ki tudja...
			}
		}
		
		$this->show('pages/calendar', $hdata, $data);
	}
}
