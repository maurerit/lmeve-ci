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
 * Description of Queue
 *
 * @author maurerit
 */
class QueueModel extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->config->load('lmconfig');
    }

    public function getQueueItem($id) {
        return $this->db
                        ->select("lmqueue.*,invTypes.typeName")
                        ->from('lmqueue')
                        ->join("`" . $this->config->item('LM_EVEDB') . "`.invTypes", "lmqueue.typeID = invTypes.typeID")
                        ->where('queueId', $id)->get()->row();
    }

    public function getQueue($year, $month) {
        $sql = $this->queueQuery($year, $month);
        return $this->db->query($sql)->result();
    }

    public function updateQueueItem($queueId, $typeId, $activityId, $quantity, $singleton) {

        $this->db
                ->where('queueId', $queueId)
                ->update('lmqueue', array('typeId' => $typeId,
                    'activityId' => $activityId,
                    'runs' => $quantity,
                    'singleton' => $singleton)
        );
    }

    public function createQueueItem($typeId, $activityId, $quantity, $singleton) {
        $this->db
                ->insert('lmqueue', array('typeId' => $typeId,
                    'activityId' => $activityId,
                    'runs' => $quantity,
                    'singleton' => $singleton)
        );
    }

    private function queueQuery($year, $month) {
        return "SELECT a.*, b.runsDone,b.jobsDone,c.jobsSuccess,d.jobsCompleted,e.runsCompleted
                  FROM (
                        SELECT itp.typeName, lmt.typeID, lmt.queueId, rac.activityName, lmt.activityID, lmt.runs
                          FROM lmqueue AS lmt
                          JOIN `" . $this->config->item('LM_EVEDB') . "`.invTypes AS itp ON lmt.typeID = itp.typeID
                          JOIN `" . $this->config->item('LM_EVEDB') . "`.ramActivities AS rac ON lmt.activityID=rac.activityID
                         WHERE ((singleton=1 AND date_format(lmt.queueCreateTimestamp, '%Y%m') = '${year}${month}') OR (singleton=0))
                        ) as a
                  LEFT JOIN (
                             SELECT lmt.queueId, SUM(aij.runs)*itp.portionSize AS runsDone, COUNT(*) AS jobsDone
                               FROM lmqueue AS lmt
                               JOIN `" . $this->config->item('LM_EVEDB') . "`.invTypes AS itp ON lmt.typeID=itp.typeID
                               JOIN apiindustryjobs aij ON lmt.typeID=aij.outputTypeID AND lmt.activityID=aij.activityID
                              WHERE date_format(beginProductionTime, '%Y%m') = '${year}${month}'
                                AND ((singleton=1 AND date_format(lmt.queueCreateTimestamp, '%Y%m') = '${year}${month}') OR (singleton=0))
                              GROUP BY lmt.typeID, lmt.activityID, lmt.queueId
                             ) AS b ON a.queueId = b.queueId
                  LEFT JOIN (
                             SELECT lmt.queueId, COUNT(*) AS jobsSuccess
                               FROM lmqueue AS lmt
                               JOIN apiindustryjobs AS aij ON lmt.typeID=aij.outputTypeID AND lmt.activityID=aij.activityID
                              WHERE aij.completed=1 AND aij.completedStatus=1 AND date_format(beginProductionTime, '%Y%m') = '${year}${month}'
                                AND ((singleton=1 AND date_format(lmt.queueCreateTimestamp, '%Y%m') = '${year}${month}') OR (singleton=0))
                              GROUP BY lmt.typeID, lmt.activityID, lmt.queueId
                             ) AS c on a.queueId = c.queueId
                  LEFT JOIN (
                             SELECT lmt.queueId, COUNT(*) AS jobsCompleted, SUM(aij.runs) * itp.portionSize AS runsCompleted
                               FROM lmqueue AS lmt
                               JOIN apiindustryjobs AS aij ON lmt.typeID=aij.outputTypeID AND lmt.activityID=aij.activityID
                               JOIN `" . $this->config->item('LM_EVEDB') . "`.invTypes itp ON lmt.typeID=itp.typeID
                              WHERE aij.completed=1 AND date_format(beginProductionTime, '%Y%m') = '${year}${month}'
                                AND ((singleton=1 AND date_format(lmt.queueCreateTimestamp, '%Y%m') = '${year}${month}') OR (singleton=0))
                              GROUP BY lmt.typeID, lmt.activityID, lmt.queueId
                             ) AS d on a.queueId = d.queueId
                  LEFT JOIN (
                             SELECT lmt.queueId, SUM(aij.runs) * itp.portionSize AS runsCompleted
                               FROM lmqueue AS lmt
                               JOIN apiindustryjobs AS aij ON lmt.typeID=aij.outputTypeID AND lmt.activityID=aij.activityID
                               JOIN `" . $this->config->item('LM_EVEDB') . "`.invTypes itp ON lmt.typeID=itp.typeID
                              WHERE date_format(beginProductionTime, '%Y%m') = '${year}${month}' AND aij.endProductionTime < UTC_TIMESTAMP()
                                AND ((singleton=1 AND date_format(lmt.queueCreateTimestamp, '%Y%m') = '${year}${month}') OR (singleton=0))
                              GROUP BY lmt.typeID, lmt.activityID, lmt.queueId
                             ) AS e on a.queueId = e.queueId
                 ORDER BY  a.typeName, a.activityName";
    }

}

?>
