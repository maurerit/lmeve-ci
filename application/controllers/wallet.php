<?php

/**
 * Description of wallet
 *
 * @author Lukas Rox
 */
class Wallet extends LMeve_Controller {

    public function index() {
        $this->template->load('layout', 'wallet', $this->data);
    }

    public function getName() {
        return 'wallet';
    }

}
