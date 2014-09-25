<?php

/**
 * Description of inventory
 *
 * @author Lukas Rox
 * @author maurerit - CI Version
 */
class Inventory extends LMeve_Controller {

    public function index() {
        $this->template->load('layout','inventory',$this->data);
    }

    public function getName() {
        return 'inventory';
    }

}
