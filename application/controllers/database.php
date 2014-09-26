<?php

/**
 * Description of database
 *
 * @author Lukas Rox
 */
class Database extends LMeve_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('itemDataModel');
    }

    public function index() {
        $this->items();
    }

    public function item($itemID) {
        
    }
    
    public function items($marketGroupID) {
        $this->data['LM_EVEDB'] = $this->config->item('LM_EVEDB');
        if (!empty($marketGroupID)) {
            $this->data['groups'] = $this->itemDataModel->getMarketGroupData($marketGroupID);
            $this->data['items'] = $this->itemDataModel->getMarketGroupItems($marketGroupID);
        } else {
            $this->data['groups'] = $this->itemDataModel->getRootMarketGroups();
        }
        $this->template->load('layout', 'database_items', $this->data);
    }

    public function orechart() {
        $this->template->load('layout', 'database_orechart', $this->data);
    }

    public function profitexplorer() {
        $this->template->load('layout', 'database_profitexplorer', $this->data);
    }

    public function profitchart() {
        $this->template->load('layout', 'database_profitchart', $this->data);
    }

    public function getName() {
        return 'database';
    }

}
