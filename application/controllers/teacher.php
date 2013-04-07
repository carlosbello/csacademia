<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * TEMPORAL. Probablemente este código deba ser heredado o integrado
 * en los controladores de estudiantes y profesores.
 *
 * @author Carlos Bello
 */
class Teacher extends Basic_controller {
    public function __construct() {
        parent::__construct();
        $this->template = 'templates/manager_page';
        $this->location = 'manager/';
        $this->menu_template = 'templates/manager_menu'; 
    }

    public function edit() {
        $this->title = "Gestión de profesores";
        $this->load_page('teacher_edit');
    }
    
    protected function _echo_json_error($error) {
        http_response_code(500);
        echo json_encode($error);
    }
    
    public function get() {
        header("Content-type:text/json");
        try {
            $this->load->model('Teacher_model');
            echo json_encode($this->Teacher_model->get_all());
        } catch (Exception $e) {
            $this->_echo_json_error($e->getMessage());
        }
    }
    
    public function add() {
        header("Content-type:text/json");
        try {
            $contact = $this->input->post();
            $this->load->model('Teacher_model');
            echo json_encode($this->Teacher_model->add($contact));
        } catch (Exception $e) {
            $this->_echo_json_error($e->getMessage());
        }
    }
    
    public function delete($id) {
            header("Content-type:text/json");
        try {
            $this->load->model('Teacher_model');
            echo json_encode($this->Teacher_model->delete($id));
        } catch (Exception $e) {
            $this->_echo_json_error($e->getMessage());
        }
    }
    
    public function update() {
        header("Content-type:text/json");
        try {
            $contact = $this->input->post();
            $this->load->model('Teacher_model');
            echo json_encode($this->Teacher_model->update($contact));
        } catch (Exception $e) {
            $this->_echo_json_error($e->getMessage());
        }
    }
}

/* End of file teacher.php */
/* Location: ./application/controllers/teacher.php */