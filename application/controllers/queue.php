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
 * The Queue is really more of a corporate work list.  Instead of micromanaging
 * tasks, now an administrator can create a bunch of 'bucket tasks' through the
 * queue here and see the status of the build from a higher level instead of
 * micromanaging the tasks per user.
 * 
 * This was born out of the reboot of LH where all the tasks being created were
 * one timers due to lack of knowledge into how much of each could/should be
 * built per month.
 * 
 * @author maurerit
 */
class Queue extends LMeve_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('queueModel');
        $this->load->model('generalModel');
        $this->load->helper('percentage_helper');
    }

    public function index($year, $month) {
        $this->requireViewQueue();
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
        $object = new stdClass();
        $object->typeId = $typeID;
        $this->load->helper('form');
        $this->data['activities'] = $this->getActivitiesAsArray();
        $this->data['selected'] = $activity;
        $this->data['queueItem'] = $object;
        $this->template->load('layout', 'queue_item', $this->data);
    }

    public function edit($id) {
        $this->requireEditQueue();
        $this->load->helper('form');
        $this->data['queueItem'] = $this->queueModel->getQueueItem($id);
        $this->data['activities'] = $this->getActivitiesAsArray();
        $this->data['queueId'] = $id;
        $this->data['selected'] = $this->data['queueItem']->activityId;
        $this->data['quantity'] = $this->data['queueItem']->runs;
        $this->data['onetime'] = $this->data['queueItem']->singleton;
        $this->template->load('layout', 'queue_item', $this->data);
    }

    public function submit() {
        $this->requireEditQueue();
        if ($this->input->post('onetime')) {
            $onetime = 1;
        } else {
            $onetime = 0;
        }

        if ($this->input->post('queueId')) {
            $this->queueModel->updateQueueItem(
                    $this->input->post('queueId'), $this->input->post('typeId'), $this->input->post('activity'), $this->input->post('quantity'), $onetime);
        } else {
            $this->queueModel->createQueueItem($this->input->post('typeId'), $this->input->post('activity'), $this->input->post('quantity'), $onetime);
        }
        redirect('/queue');
    }
    
    public function delete($queueId) {
        $this->queueModel->delete($queueId);
        redirect('/queue');
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

    private function requireEditQueue() {
        if (!has_permissions($this->data['permissions'], 'Administrator,EditQueue')) {
            $this->template->load('layout', 'unauthorized');
        }
    }

    private function requireViewQueue() {
        if (!has_permissions($this->data['permissions'], 'Administrator,ViewQueue')) {
            $this->template->load('layout', 'unauthorized');
        }
    }

}

?>
