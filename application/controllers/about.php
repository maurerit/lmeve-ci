<?php

/**
 * Description of about
 *
 * @author Lukas Rox
 * @author maurerit - CI Version
 */
class About extends LMeve_Controller {

    public function index() {
        $this->template->load('layout', 'about', $this->data);
    }

    public function history() {
        $this->data['history'] = 1;
        $this->template->load('layout', 'about', $this->data);
    }

    public function getName() {
        return 'about';
    }

}

?>
