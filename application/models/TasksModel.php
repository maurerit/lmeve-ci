<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TasksModel
 *
 * @author mm66053
 */
class TasksModel extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->config->load('lmconfig');
    }

    public function getAllPoints() {
        $sql = "SELECT rac.`activityName`,cpt.* FROM `" . $this->config->item('LM_EVEDB') . "`.`ramActivities` rac JOIN `cfgpoints` cpt ON rac.`activityID`=cpt.`activityID` ORDER BY `activityName`";
        return $this->db->query($sql)->result();
    }

    public function getAllStats($corp, $year, $month) {
        $sql = "SELECT `activityName`, COUNT(*) AS jobs, SUM(TIME_TO_SEC(TIMEDIFF(`endProductionTime`,`beginProductionTime`))/3600) AS hours
	FROM `apiindustryjobs` aij
	JOIN `" . $this->config->item('LM_EVEDB') . "`.`ramActivities` rac
	ON aij.activityID=rac.activityID
	WHERE date_format(beginProductionTime, '%Y%m') = '${year}${month}'
	AND aij.corporationID='$corp'
	GROUP BY `activityName`
	ORDER BY `activityName`;";
        return $this->db->query($sql)->result();
    }

}
