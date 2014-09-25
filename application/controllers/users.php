<?php

/**
 * Description of users
 *
 * @author Lukas Rox
 */
class Users extends LMeve_Controller {

    public function index() {
        $this->template->load('layout', 'users', $this->data);
    }

    public function getName() {
        return 'users';
    }

}
