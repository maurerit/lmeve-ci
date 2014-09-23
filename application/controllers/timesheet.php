<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of timesheet
 *
 * @author mm66053
 */
class Timesheet extends LMeve_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('generalModel');
        $this->load->model('tasksModel');
    }

    public function index() {
        $this->data['corps'] = $this->generalModel->getAllCorps();
        $this->data['ONEPOINT'] = $this->generalModel->getIskPerPoint();
        $this->data['points'] = $this->tasksModel->getAllPoints();
        $this->data['stats'] = array();
        foreach ($this->data['corps'] as $corp) {
            $this->data['stats'][$corp->corporationID] = $this->tasksModel->getAllStats($corp->corporationID, $this->data['year'], $this->data['month']);
        }
        $this->data['mychars'] = $this->userModel->getCharacters($this->data['userID']);
        $this->data['rearrange'] = $this->tasksModel->getAllWagesByActivity($this->data['ONEPOINT'], 1211383288, $this->data['year'], $this->data['month']);
        $this->template->load('layout', 'timesheet', $this->data);
    }

    public function getName() {
        return 'timesheet';
    }

}
