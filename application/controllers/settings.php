<?php

/**
 * Description of settings
 *
 * @author Lukas Rox
 */
class Settings extends LMeve_Controller {

    public function index() {
        $this->template->load('layout', 'settings_main', $this->data);
    }

    public function getName() {
        return 'settings';
    }

}
