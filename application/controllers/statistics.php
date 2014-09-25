<?php

/**
 * Description of statistics
 *
 * @author Lukas Rox
 */
class Statistics extends LMeve_Controller {

    public function index() {
        $this->template->load('layout', 'statistics_main', $this->data);
    }

    public function getName() {
        return 'statistics';
    }

}
