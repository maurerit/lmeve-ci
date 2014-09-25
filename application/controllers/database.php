<?php

/**
 * Description of database
 *
 * @author Lukas Rox
 */
class Database extends LMeve_Controller {

    public function index() {
        $this->template->load('layout','database_main',$this->data);
    }

    public function getName() {
        return 'database';
    }

}
