<?php

/**
 * Description of market
 *
 * @author Lukas Rox
 */
class Market extends LMeve_Controller {

    public function index() {
        $this->template->load('layout', 'market', $this->data);
    }

    public function getName() {
        return 'market';
    }

}
