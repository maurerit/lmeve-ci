<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CorpsModel
 *
 * @author mm66053
 */
class GeneralModel extends CI_Model {

    public function getAllCorps() {
        return $this->db->get('apicorps')->result();
    }

    public function getIskPerPoint() {
        return $this->db->select('itemValue')->where('itemLabel','iskPerPoint')->limit(1)->get('lmconfig')->row()->itemValue;
    }

}
