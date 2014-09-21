<?php

class Main extends LMeve_Controller {

    public function index() {
        if ($this->session->userdata('granted')) {

        } else {
            $this->loadCsrf();
            $this->load->view('login', $this->data);
        }
    }

    public function getName() {
        return 'main';
    }

}

?>