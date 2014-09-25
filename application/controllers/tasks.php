<?php

/**
 * Description of tasks
 *
 * @author Lukas Rox
 */
class Tasks extends LMeve_Controller {

    public function index() {
        $this->template->load('layout', 'tasks', $this->data);
    }

    public function getName() {
        return 'tasks';
    }

}
