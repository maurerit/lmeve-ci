<?php

/*
 * Copyright 2014 maurerit.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * Description of ItemDataModel
 *
 * @author Lukas Rox
 * @author maurerit - CI Version
 */
class ItemDataModel extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->config('lmconfig');
    }

    public function getMarketGroupData($marketGroupID) {
        $data = $this->db->query("SELECT * FROM `" . $this->config->item('LM_EVEDB') . "`.`invMarketGroups` WHERE `parentGroupID` = $marketGroupID ;");
        if ($data->num_rows > 0) {
            return($data->result());
        } else {
            return;
        }
    }

    public function getRootMarketGroups() {
        return $this->db->where('parentGroupID is null', null, false)->get("`" . $this->config->item('LM_EVEDB') . "`.invMarketGroups")->result();
    }

    public function getMarketGroupItems($marketGroupID) {
        $items = $this->db->query("SELECT itp.`typeID`, itp.`typeName`
				FROM `" . $this->config->item('LM_EVEDB') . "`.`invTypes` itp			
				WHERE `marketGroupID` =  $marketGroupID
				AND published = 1
				LIMIT 50;")->result();

        return $items;
    }

}
