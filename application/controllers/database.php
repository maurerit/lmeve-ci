<?php

/**
 * Description of database
 *
 * @author Lukas Rox
 */
class Database extends LMeve_Controller {

    public function index() {
        $this->template->load('layout', 'database_main', $this->data);
    }

    public function items() {
        $this->template->load('layout','database_items',$this->data);
    }

    public function orechart() {
        $this->template->load('layout','database_orechart',$this->data);
    }
    
    public function profitexplorer() {
        $this->template->load('layout','database_profitexplorer',$this->data);
    }
    
    public function profitchart() {
        $this->template->load('layout','database_profitchart',$this->data);
    }

    public function getName() {
        return 'database';
    }

}
