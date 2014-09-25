<?php

/**
 * Description of characters
 *
 * @author Lukas Rox
 */
class Characters extends LMeve_Controller {
    public function index() {
        $this->template->load('layout','characters_main',$this->data);
    }

    public function getName() {
        return 'characters';
    }

}
