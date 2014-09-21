<?php

class Welcome extends LMeve_Controller {

    public function index() {
        $this->template->load('layout', 'content', $this->data);
    }

    public function getName() {
        return 'welcome';
    }

}

