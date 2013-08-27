<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Gestión de los datos de un estudiante.
 *
 * @author Carlos Bello
 */
class Student extends Basic_controller {

    var $levels;
    var $academicPeriods;
    var $leaveReasons;
    var $relationships;
    var $schoolLevels;

    public function __construct() {
        parent::__construct();
        $this->template = 'templates/manager_page';
        $this->location = 'manager/';
        $this->menu_template = 'templates/manager_menu';
    }

    public function admin() {
        $this->current_page();
        $this->title = lang('page_manage_students');
        $this->subject = lang('subject_student');
        $this->load->model('Group_model');
        $this->load->model('General_model');
        $this->editMode = is_null($this->session->userdata('current_center')['id']) ? 'false' : 'true';
        $this->levels = $this->db->select("code, description")->from('level')->get()->result_array();
        $this->groups = $this->Group_model->get_all();
        $this->leaveReasons = $this->db->select("code, description")->from('leave_reason')->get()->result_array();
        $this->relationships = $this->db->select("code, name")->from('family_relationship')->get()->result_array();
        $this->schoolLevels = $this->db->select("id, name")->from('school_level')->get()->result_array();
        $this->payments_types = $this->General_model->get_fields('payment_type', 'id, name');
        $this->academicPeriods = $this->General_model->get_fields('academic_period', 'code, name');
        $this->load_page('student_admin');
    }

    protected function _echo_json_error($error) {
        http_response_code(500);
        echo json_encode($error);
    }

    public function get() {
        $this->setup_ajax_response_headers();
        try {
            $filter = $this->input->post();
            if (!is_array($filter))
                $filter = [];
            $this->load->model('Student_model');
            echo json_encode($this->Student_model->get_all($filter));
        } catch (Exception $e) {
            $this->_echo_json_error($e->getMessage());
        }
    }

    public function add() {
        $this->setup_ajax_response_headers();
        try {
            $contact = $this->input->post();
            $this->load->model('Student_model');
            echo json_encode($this->Student_model->add($contact));
        } catch (Exception $e) {
            $this->_echo_json_error($e->getMessage());
        }
    }

    public function delete($id) {
        $this->setup_ajax_response_headers();
        try {
            $this->load->model('Student_model');
            echo json_encode($this->Student_model->delete($id));
        } catch (Exception $e) {
            $this->_echo_json_error($e->getMessage());
        }
    }

    public function update() {
        $this->setup_ajax_response_headers();
        try {
            $contact = $this->input->post();
            $this->load->model('Student_model');
            echo json_encode($this->Student_model->update($contact));
        } catch (Exception $e) {
            $this->_echo_json_error($e->getMessage());
        }
    }

    public function family_get($id) {
        $this->setup_ajax_response_headers();
        try {
            $this->load->model('Family_model');
            echo json_encode($this->Family_model->get_all($id));
        } catch (Exception $e) {
            $this->_echo_json_error($e->getMessage());
        }
    }
    
    public function family_get_available() {
        $this->setup_ajax_response_headers();
        try {
            $this->load->model('Family_model');
            echo json_encode($this->Family_model->get_available());
        } catch (Exception $e) {
            $this->_echo_json_error($e->getMessage());
        }
    }

    public function family_delete($student_id, $contact_id) {
        $this->setup_ajax_response_headers();
        try {
            $this->load->model('Family_model');
            echo json_encode($this->Family_model->delete($student_id, $contact_id));
        } catch (Exception $e) {
            $this->_echo_json_error($e->getMessage());
        }
    }

    public function family_add() {
        $this->setup_ajax_response_headers();
        try {
            $family = $this->input->post();
            $this->load->model('Family_model');
            echo json_encode($this->Family_model->add($family));
        } catch (Exception $e) {
            $this->_echo_json_error($e->getMessage());
        }
    }

    public function family_update() {
        $this->setup_ajax_response_headers();
        try {
            $family = $this->input->post();
            $this->load->model('Family_model');
            echo json_encode($this->Family_model->update($family));
        } catch (Exception $e) {
            $this->_echo_json_error($e->getMessage());
        }
    }
    
    public function family_relate() {
        $this->setup_ajax_response_headers();
        try {
            $family = $this->input->post();
            $this->load->model('Family_model');
            echo json_encode($this->Family_model->relate($family));
        } catch (Exception $e) {
            $this->_echo_json_error($e->getMessage());
        }        
    }

    public function get_price_by_student($student_id) {
        $this->setup_ajax_response_headers();
        try {
            $this->load->model('Student_model');
            echo json_encode($this->Student_model->get_price_by_student($student_id));
        } catch (Exception $e) {
            $this->_echo_json_error($e->getMessage());
        }
    }

    public function payments_get($id) {
        $this->setup_ajax_response_headers();
        try {
            $this->load->model('Payment_model');
            echo json_encode($this->Payment_model->get_all($id));
        } catch (Exception $e) {
            $this->_echo_json_error($e->getMessage());
        }
    }

    public function payment_delete($id) {
        $this->setup_ajax_response_headers();
        try {
            $this->load->model('Payment_model');
            echo json_encode($this->Payment_model->delete($id));
        } catch (Exception $e) {
            $this->_echo_json_error($e->getMessage());
        }
    }

    public function payment_add() {
        $this->setup_ajax_response_headers();
        try {
            $payment = $this->input->post();
            $this->load->model('Payment_model');
            echo json_encode($this->Payment_model->add($payment));
        } catch (Exception $e) {
            $this->_echo_json_error($e->getMessage());
        }
    }

    public function payment_update() {
        $this->setup_ajax_response_headers();
        try {
            $payment = $this->input->post();
            $this->load->model('Payment_model');
            echo json_encode($this->Payment_model->update($payment));
        } catch (Exception $e) {
            $this->_echo_json_error($e->getMessage());
        }
    }

    public function payments_report($id) {

        /* $this->setup_ajax_response_headers();
          try {
          $this->load->model('Payment_model');
          echo json_encode($this->Payment_model->get_all($id));
          } catch (Exception $e) {
          $this->_echo_json_error($e->getMessage());
          } */

        /* $this->load->model('Payment_model');
          //echo json_encode($this->Payment_model->get_all($id));
          $this->load->library('PHPReport');
          $xxx = $this->Payment_model->get_all($id);
          $R = new PHPReport();
          $R->load(array(
          'id' => 'product',
          'data' => $xxx
          )

          );

          $R->render(); */
        try {
            $this->load->model('Payment_model');
            $this->load->model('General_model');
            $this->load->helper('Util_helper');

            $payments = $this->Payment_model->get_all($id);
            $student = $this->General_model->get_where('contact', 'id = ' . $id);
            $this->load->library('mpdf');
            $mpdf = new mPDF('c', 'A4');
            $html = '
<style>
.td_center{
        text-align:center; 
        padding: 0 0.5em;
}
.td_right{
        text-align:right; 
        padding: 0 0.5em;
}
.gradient {
	border:0.1mm solid #220044; 
	background-color: #f0f2ff;
	background-gradient: linear #c7cdde #f0f2ff 0 1 0 0.5;
	box-shadow: 0.3em 0.3em #888888;
}
.rounded {
	border:0.1mm solid #220044; 
	background-color: #f0f2ff;
	background-gradient: linear #c7cdde #f0f2ff 0 1 0 0.5;
	border-radius: 2mm;
	background-clip: border-box;
}

table.list {
	border:1px solid #000000;
	font-family: sans-serif; /*sans-serif; Arial Unicode MS;*/
	font-size: 10pt;
	background-gradient: linear #c7cdde #f0f2ff 0 1 0 0.5;
}
table.list td, th {
	border:1px solid #000000;
	text-align: left;
	font-weight: normal;
}
.title-font{

}
</style>
<body>

<table border="0" width="100%" >
<tbody>
<tr>
<td rowspan="2" style="text-align: right;"><img src="/assets/img/logo.png" width="140" /></td>
<td><p><b>Informe de Pagos</b></td>
</tr>
<tr>
<td><p>Alumno: ';
$html .= $student[0]['first_name'] . ' ' .$student[0]['last_name'];            
$html .= '</p></td>
</tr>
</tbody>
</table>
';
            
            $html .= '<table class="list1" border="1" width="100%"  style="border-collapse: collapse">';
            $html .= '<thead><tr>';
            $html .= '<td class="td_center">#</td>';
            $html .= '<td class="td_center">Fecha</td>';
            $html .= '<td class="td_center">Tipo de pago</td>';
            $html .= '<td class="td_center">Periodo</td>';
            $html .= '<td class="td_right">Importe</td>';
            $html .= '</tr></thead><tbody>';
            $count = 1;
            foreach ($payments AS $payment) {
                $dateNormal = db_to_Local($payment['date']);
                $html .= '<tr><td class="td_center">' . $count . '</td>';
                $html .= '<td class="td_center">' . $dateNormal . '</td>';
                $html .= '<td class="td_center">' . $payment['payment_type_name'] . '</td>';
                $html .= '<td class="td_center">' . $payment['piriod'] . '</td>';
                $html .= '<td class="td_right">' . $payment['amount'] . '</td></tr>';
                $count++;
            }
            $html .='</tbody></table>
<br />
<body>';
            //$this->setup_ajax_response_headers();
            //header("Content-Type: text/plain");
            //header('Content-type: application/pdf');
            $mpdf->WriteHTML($html);
            $mpdf->Output('pagos.pdf', 'I'); //exit;
        } catch (Exception $e) {
            $this->_echo_json_error($e->getMessage());
        }
    }

    /*public function payment_report($id) {

        try {
            $this->load->model('Payment_model');

            $payment = $this->Payment_model->get_payment_id($id);
            $this->load->library('mpdf');
            $mpdf = new mPDF('c', array(100, 100));

            $html = '
              <style>
              .td_center{
              text-align:center;
              padding: 0 0.5em;
              }
              .td_right{
              text-align:right;
              padding: 0 0.5em;
              }

              .gradient {
              border:0.1mm solid #220044;
              background-color: #f0f2ff;
              background-gradient: linear #c7cdde #f0f2ff 0 1 0 0.5;
              box-shadow: 0.3em 0.3em #888888;
              }
              .rounded {
              border:0.1mm solid #220044;
              background-color: #f0f2ff;
              background-gradient: linear #c7cdde #f0f2ff 0 1 0 0.5;
              border-radius: 2mm;
              background-clip: border-box;
              }
              div.text {
              padding:0.8em;
              margin-bottom: 0.7em;
              }
              p {
              margin: 0.25em 0;
              }
              table.list {
              border:1px solid #000000;
              font-family: sans-serif;
              font-size: 10pt;
              background-gradient: linear #c7cdde #f0f2ff 0 1 0 0.5;
              }
              table.list td, th {
              border:1px solid #000000;
              text-align: left;
              font-weight: normal;
              }
              .code {
              font-family: monospace;
              font-size: 9pt;
              background-color: #d5d5d5;
              margin: 0.5em 0 0.5cm 0;
              padding: 0 0.3cm;
              border:0.2mm solid #000088;
              box-shadow: 0.3em 0.3em #888888;
              }
              </style>
              <body>

              <div class="gradient text rounded">
              <table border="0" width="100%" >
              <tbody>
              <tr>
              <td rowspan="2" style=""><img src="/assets/img/logo.png" width="140" /></td>
              <td><p style="font-size: 20px">RECIBO</td>
              </tr>
              <tr>
              <td><p>
              ';
            $html .= 'Fecha: ' . $payment['date'];
            $html .= '
              </p></td>
              </tr>
              </tbody>
              </table>
              </div>

              <div class="gradient text rounded">
              <p> Recibí de: </p>
              
              <p> </p>
              
              <p>Por: Pago
              ';
            //<p>La cantidad de euros: </p>
            //<p class="code"> <br> <br></p>
            $html .= $payment['payment_type_name'] . '  ' . $payment['piriod'];
            $html .= '</p>
              <p > </p>
              <p>€
              ';
            $html .= $payment['amount'];
            $html .= '
              Firmado: ______________</p>
              </div>
              </body>';
            //$this->setup_ajax_response_headers();
            //header("Content-Type: text/plain");
            //header('Content-type: application/pdf');
            $mpdf->WriteHTML($html);
            $mpdf->Output('pagos.pdf', 'I'); //exit;
        } catch (Exception $e) {
            $this->_echo_json_error($e->getMessage());
        }
    }*/
    
    public function payment_report($id) {

        try {
            $this->load->library('mpdf');
            //$header = 'Document header ' . $id;
            //$html1 = 'Your document content goes here';
            //$footer = 'Print date: ' . date('d.m.Y H:i:s') . '<br />Page {PAGENO} of {nb}';
            
            $this->load->model('Payment_model');
            $this->load->helper('Util_helper');
            $payment = $this->Payment_model->get_payment_id($id);
            
            $dateDB = $payment['date'];
            
            $dateNormal = db_to_Local($dateDB);
            
            /*if (($dateDB == "") || ($dateDB == "0000-00-00")) {
                $dateNormal = "";
            } else {
                $dateArray = explode("-", $dateDB);
                $aux = $dateArray[2];
                $dateArray[2] = $dateArray[0];
                $dateArray[0] = $aux;
                $dateNormal = implode("/", $dateArray);
            }*/
            
            $html = '
            <body>
              <div class="gradient text rounded">
              <table border="0" width="100%" >
              <tbody>
              <tr>
              <td rowspan="2" style=""><img src="/assets/img/logo.png" width="140" /></td>
              <td><p style="font-size: 20px">RECIBO</td>
              </tr>
              <tr>
              <td><p>
              ';
            $html .= 'Fecha: ' . $dateNormal;
            $html .= '
              </p></td>
              </tr>
              </tbody>
              </table>
              </div>
              <div class="gradient text rounded">
              ';
            $html .= '<p> Recibí de: ' . $payment['first_name'] . ' ' . $payment['last_name'] . '</p>';
            $html .= '  
              
              <p>Por: Pago
              ';
            //<p>La cantidad de euros: </p>
            //<p class="code"> <br> <br></p>
            $html .= $payment['payment_type_name'] . '  ' . $payment['piriod'];
            $html .= '</p>
             
              <p>Importe: €
              ';
            $html .= $payment['amount'];
            $html .= '
              </p><p>Firmado: ______________</p>
              </div>
              </body>';

            //$mpdf = new mPDF('utf-8', 'A4', 0, '', 12, 12, 25, 15, 12, 12);
            $mpdf = new mPDF('c', array(100, 100));
            //$mpdf->SetHTMLHeader($header);
            //$mpdf->SetHTMLFooter($footer);
            $mpdf->SetJS('this.print();');
            $mpdf->WriteHTML($html);
            $mpdf->Output();
            
         } catch (Exception $e) {
            $this->_echo_json_error($e->getMessage());
        }
    }

    public function qualification_get($student_id) {
        $this->setup_ajax_response_headers();
        try {
            echo json_encode($this->General_model->get_where('qualification', "student_id = '".$student_id."'"));
        } catch (Exception $e) {
            $this->_echo_json_error($e->getMessage());
        }       
    }
    
    public function qualification_add() {
        $this->setup_ajax_response_headers();
        try {
            $qualification = $this->input->post();
            $this->General_model->insert('qualification', $qualification);
            echo true;
        } catch (Exception $e) {
            $this->_echo_json_error($e->getMessage());
        }
    }
    
    public function qualification_update() {
        $this->setup_ajax_response_headers();
        try {
            $qualification = $this->input->post();
            $where = ['student_id' => $qualification['student_id'], 
                'academic_period' => $qualification['academic_period']];
            unset($qualification['student_id']);
            unset($qualification['academic_period']);
            $this->General_model->update('qualification', $qualification, $where);
            echo true;
        } catch (Exception $e) {
            $this->_echo_json_error($e->getMessage());
        }
    }
    
        public function qualification_delete($student_id, $academic_period) {
        $this->setup_ajax_response_headers();
        try {
            $where = ['student_id' => $student_id, 
                'academic_period' => $academic_period];
            $this->General_model->delete('qualification', $where);
            echo true;
        } catch (Exception $e) {
            $this->_echo_json_error($e->getMessage());
        }
    }
}

/* End of file teacher.php */
/* Location: ./application/controllers/teacher.php */  
