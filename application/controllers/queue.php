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

class Queue extends LMeve_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('queueModel');
        $this->load->model('generalModel');
        $this->load->helper('percentage_helper');
    }

    function _remap($method) {
        $param_offset = 2;

        // Default to index
        if (!method_exists($this, $method)) {
            // We need one more param
            $param_offset = 1;
            $method = 'index';
        }

        // Since all we get is $method, load up everything else in the URI
        $params = array_slice($this->uri->rsegment_array(), $param_offset);

        // Call the determined method with all params
        call_user_func_array(array($this, $method), $params);
    }

    public function index($year, $month) {
        if (!$year) {
            $year = date('Y');
            $month = date('m');
        }
        $this->data['year'] = $year;
        $this->data['month'] = $month;
        $this->data['queueItems'] = $this->queueModel->getQueue($year, $month);
        $this->template->load('layout', 'queue', $this->data);
    }

    public function newQueueItem($activity, $typeID) {
        $this->load->helper('form');
        $this->data['activities'] = $this->getActivitiesAsArray();
        $this->template->load('layout', 'queue_item', $this->data);
    }

    public function edit($id) {
        $this->load->helper('form');
        $this->data['queueItem'] = $this->queueModel->getQueueItem($id);
        $this->data['activities'] = $this->getActivitiesAsArray();
        $this->data['queueId'] = $id;
        $this->data['selected'] = $this->data['queueItem']->activityId;
        $this->data['quantity'] = $this->data['queueItem']->runs;
        $this->data['onetime'] = $this->data['queueItem']->singleton;
        $this->template->load('layout', 'queue_item', $this->data);
    }

    public function editSubmit() {
        echo 'Hello';
    }

    public function getName() {
        return 'queue';
    }

    private function getActivitiesAsArray() {
        $actRes = $this->generalModel->getActivities();
        $activities = array();

        foreach ($actRes as $activity) {
            $activities[$activity->activityID] = $activity->activityName;
        }

        return $activities;
    }

}

?>
