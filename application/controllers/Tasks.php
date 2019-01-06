<?php
// Különböző alkalmanként vagy időzítve végrehajtandó backend-feladatok vezérlője
require_once('Base.php');
class Tasks extends Base {

	//Konstruktor.
	public function __construct()
	{
        parent::__construct();
        $this->load->library('session');
		$this->load->model('tasks_model');
    }
    
    private function run_if_authorized($func)
    {
        if ($this->session->userdata('id')) {
            $this->tasks_model->$func();
        } else {
            echo 'NOT AUTHORIZED.';
        }
    }
    
	public function remove_mutat_php_links()
	{
        $this->run_if_authorized('remove_mutat_php_links');
    }
    
	public function cikkek_kepei_replace()
	{
        $this->run_if_authorized('cikkek_kepei_replace');
    }
    
	public function refactor_article_body_formatting()
	{
        $this->run_if_authorized('refactor_article_body_formatting');
	}
}
