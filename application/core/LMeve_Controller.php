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
 * This controller should be extended by any controller that requires authentication.
 * It has basic functionality for loging in and loging out and also determining the
 * users access privileges.
 *
 * @author maurerit
 */
abstract class LMeve_Controller extends CI_Controller {

    public $data = array();

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
    
    function __construct() {
        parent::__construct();
        session_start();

        if (ENVIRONMENT === "development") {
            $this->output->enable_profiler(TRUE);
        }

        $this->benchmark->mark('LMeveControllerLoad_start');
        $this->load->library('session');
        $this->load->model('userModel');
        $this->config->load('lmconfig');
        $this->load->helper('permission_helper');
        $this->loadCsrf();
        $this->benchmark->mark('LMeveControllerLoad_end');

        $granted = $this->session->userdata('granted');

        $this->benchmark->mark('LMeveControllerSetData_start');
        $this->data['LM_APP_NAME'] = $this->config->item('LM_APP_NAME');
        $this->data['lmver'] = $this->config->item('LM_VERSION');
        $this->data['stylesheet'] = $this->userModel->getDefaultCss();
        $this->data['year'] = date('Y');
        $this->data['month'] = date('m');
        $this->data['THOUSAND_SEP'] = $this->config->item('THOUSAND_SEP');
        $this->data['DECIMAL_SEP'] = $this->config->item('DECIMAL_SEP');
        $this->benchmark->mark('LMeveControllerSetData_end');

        $this->benchmark->mark('LMeveControllerSession_start');
        if (!$granted && $_SERVER['REQUEST_URI'] !== '/main.html' && $_SERVER['REQUEST_URI'] !== '/main/login.html') {
            //TODO: Make this configurable

            redirect('main');
        } else if ($granted) {
            $user = $this->userModel->getUser($this->session->userdata('username'));
            $this->data['stylesheet'] = $user->css;
            $this->data['username'] = $user->login;
            $this->data['userID'] = $user->userID;
            $this->data['permissions'] = $this->userModel->getPermissions($user->userID);
            $this->loadMenu($this->config->item('LM_MENU'));
            $this->loadSidebar();
        }
        $this->benchmark->mark('LMeveControllerSession_end');
    }

    public function login() {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('login', 'Login', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() !== false) {
            $user = $this->userModel->authUser($this->input->post('login'), $this->input->post('password'));
            if ($user !== false) {
                $this->session->set_userdata('granted', true);
                $this->session->set_userdata('username', $user->login);
                redirect('welcome');
            } else {
                $this->load->view('login', 'login', $this->data);
            }
        } else {
            $this->load->view('login', $this->data);
        }
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('main');
    }

    public function loadCsrf() {
        $this->data['csrf_token_name'] = $this->security->get_csrf_token_name();
        $this->data['csrf_token_hash'] = $this->security->get_csrf_hash();
    }

    public function loadMenu($menuConfigs) {
        $this->benchmark->mark('LMeveControllerLoadMenu_start');
        $menu = '';

        foreach ($menuConfigs as $menuConfig) {
            $class = 'menu';
            if ($this->getName() === strtolower($menuConfig['name'])) {
                $class = 'menua';
            }
            if (has_permissions($this->data['permissions'], "Administrator," . $menuConfig['rootPerm']))
                $menu = $menu . '<td class="' . $class . '"> <a href="' . $menuConfig['path'] . '">' . $menuConfig['name'] . '</a><br></td>';
        }

        $this->data['menu'] = $menu;
        $this->benchmark->mark('LMeveControllerLoadMenu_end');
    }

    public function loadSidebar() {
        $this->data['sidebar'] = '';
    }

    public abstract function getName();
}

?>
