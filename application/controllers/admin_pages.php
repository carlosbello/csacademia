<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Controlador para las páginas de administración que no requieren mucho 
 * procesamiento, del lado del servidor.
 *
 * @author Carlos Bello
 * @author Leonardo Quintero
 */
class Admin_pages extends Crud_controller {
   
    public function __construct() {
        parent::__construct();
        $this->template = 'templates/crud_template';
        $this->description = 'Página de administración general';
        $this->menu_template = 'templates/manager_menu';       
    }

    public function client() {
        $this->set_page_title('page_manage_clients');

        $this->crud->set_table('client');
        $this->crud->set_subject(lang('subject_client'));
        $this->crud->columns('id', 'name');
        $this->crud->display_as('id', lang('form_id'))
                ->display_as('name', lang('form_name'));

        $this->crud->required_fields('name');
        $this->crud->fields('name');
        $this->crud_view = $this->crud->render();

        $this->load_page();
    }

    public function role() {
        $this->set_page_title('page_manage_roles');

        $this->crud->set_table('role');
        $this->crud->set_subject(lang('subject_role'));
        $this->crud->columns('code', 'description');
        $this->crud->display_as('code', lang('form_id'))
                ->display_as('description', lang('form_description'));
        $this->crud->required_fields('code', 'description');
        $this->crud->fields('code', 'description');
        $this->crud_view = $this->crud->render();

        $this->load_page();
    }

    public function user() {
        $this->set_page_title('page_manage_users');
        $fields = array('id', 'email', 'client_id', 'role_code');

        if ($this->role_id != ROLE_ADMINISTRATOR) {
            $this->crud->where('client_id', $this->client_id);
            $fields = array('id', 'email', 'role_code');
        }

        $this->crud->set_table('login');
        $this->crud->set_subject(lang('subject_user'));
        $this->crud->columns($fields);
        $this->crud->display_as('id', lang('form_id'))
                ->display_as('email', lang('form_email'))
                ->display_as('password', lang('form_password'))
                ->display_as('client_id', lang('form_client'))
                ->display_as('role_code', lang('form_role'));

        $this->crud->required_fields('email', 'password', 'client_id', 'role_code');
        $this->crud->fields('email', 'password', 'client_id', 'role_code');
        $this->crud->change_field_type('password', 'password');
        $this->crud->set_relation('client_id', 'client', 'name');
        $this->crud->set_relation('role_code', 'role', 'description');
        if ($this->role_id != ROLE_ADMINISTRATOR) {
            $this->crud->field_type('client_id', 'hidden', $this->client_id);
        }

        $this->crud->callback_edit_field('password', array($this, 'set_password_input_to_empty'));
        $this->crud->callback_add_field('password', array($this, 'set_password_input_to_empty'));
        $this->crud->callback_before_update(array($this, 'encrypt_password_callback'));
        $this->crud->callback_before_insert(array($this, 'encrypt_password_callback'));

        $this->crud_view = $this->crud->render();
        $this->load_page();
    }

    function encrypt_password_callback($post_array) {
        if (!empty($post_array['password'])) {
            $post_array['password'] = md5($post_array['password']);
        } else {
            unset($post_array['password']);
        }
        return $post_array;
    }

    function set_password_input_to_empty() {
        return "<input type='password' name='password' value='' />";
    }

    public function center() {
        $this->set_page_title('page_manage_centers');
        $fields = array('id', 'client_id', 'name');

        if ($this->role_id != ROLE_ADMINISTRATOR) {
            $this->crud->where('client_id', $this->client_id);
            $fields = array('id', 'name');
        }

        $this->crud->set_table('center');
        $this->crud->set_subject(lang('subject_center'));
        $this->crud->columns($fields);
        $this->crud->display_as('id', lang('form_id'))
                ->display_as('client_id', lang('form_client'))
                ->display_as('name', lang('form_name'));

        $this->crud->required_fields('name');
        $this->crud->fields('client_id', 'name');
        $this->crud->set_relation('client_id', 'client', 'name');
        if ($this->role_id != ROLE_ADMINISTRATOR) {
            $this->crud->field_type('client_id', 'hidden', $this->client_id);
        }

        $this->crud_view = $this->crud->render();
        $this->load_page();
    }

    public function level() {
        $this->set_page_title('page_manage_levels');
        $fields = array('code', 'description', 'price', 'client_id');

        if ($this->role_id != ROLE_ADMINISTRATOR) {
            $this->crud->where('client_id', $this->client_id);
            $fields = array('code', 'description', 'price');
        }

        $this->crud->set_table('level');
        $this->crud->set_subject(lang('subject_level'));
        $this->crud->columns($fields);
        $this->crud->display_as('code', lang('form_id'))
                ->display_as('description', lang('form_description'))
                ->display_as('price', lang('form_price'))
                ->display_as('client_id', lang('form_client'));

        $this->crud->required_fields('code', 'description', 'client_id');
        $this->crud->fields('code', 'description', 'price', 'client_id');
        $this->crud->set_relation('client_id', 'client', 'name');
        if ($this->role_id != ROLE_ADMINISTRATOR) {
            $this->crud->field_type('client_id', 'hidden', $this->client_id);
        }

        $this->crud_view = $this->crud->render();
        $this->load_page();
    }

    public function family_relationship() {
        $this->set_page_title('page_manage_relationships');

        $this->crud->set_table('family_relationship');
        $this->crud->set_subject(lang('subject_family_relationship'));
        $this->crud->columns('code', 'name');
        $this->crud->display_as('code', lang('form_id'))
                ->display_as('name', lang('form_name'));

        $this->crud->required_fields('code', 'name');
        $this->crud->fields('code', 'name');

        $this->crud_view = $this->crud->render();
        $this->load_page();
    }

    public function contact() {
        $this->set_page_title('page_manage_contacts');

        $this->crud->set_table('contact');
        $this->crud->set_subject(lang('subject_contact'));
        $this->crud->columns('id', 'client_id', 'first_name', 'last_name', 'id_card', 'sex', 'email', 'phone_mobile', 'phone', 'picture', 'notes', 'address', 'postal_code', 'town', 'province', 'date_of_birth', 'occupation');
        $this->crud->display_as('id', lang('form_id'))
                ->display_as('client_id', lang('form_client'))
                ->display_as('first_name', lang('form_first_name'))
                ->display_as('last_name', lang('form_last_name'))
                ->display_as('sex', lang('form_sex'))
                ->display_as('email', lang('form_email'))
                ->display_as('phone_mobile', lang('form_phone_mobile'))
                ->display_as('phone', lang('form_phone'))
                ->display_as('picture', lang('form_photo'))
                ->display_as('notes', lang('form_notes'))
                ->display_as('address', lang('form_address'))
                ->display_as('postal_code', lang('form_postal_code'))
                ->display_as('town', lang('form_town'))
                ->display_as('province', lang('form_province'))
                ->display_as('date_of_birth', lang('form_date_of_birth'))
                ->display_as('occupation', lang('form_occupation'))
                ->display_as('id_card', lang('form_id_card'));

        $this->crud->required_fields('client', 'first_name', 'last_name', 'id_card', 'sex', 'email', 'address', 'town', 'province', 'date_of_birth');
        $this->crud->fields('client_id', 'first_name', 'last_name', 'id_card', 'sex', 'email', 'phone_mobile', 'phone', 'picture', 'notes', 'address', 'postal_code', 'town', 'province', 'date_of_birth', 'occupation');
        $this->crud->set_field_upload('picture', 'assets/uploads/files/contact');
        $this->crud->change_field_type('notes', 'text');
        $this->crud->field_type('date_of_birth','date');
        $this->crud->field_type('sex', 'dropdown', array('M' => lang('form_sex_male'), 'F' => lang('form_sex_female')));
        $this->crud->set_relation('client_id', 'client', 'name');

        $this->crud_view = $this->crud->render();
        $this->load_page();
    }

    public function leave_reason() {
        $this->set_page_title('page_manage_leave_reasons');

        $this->crud->set_table('leave_reason');
        $this->crud->set_subject(lang('subject_leave_reason'));
        $this->crud->columns('code', 'description');
        $this->crud->display_as('code', lang('form_id'))
                ->display_as('description', lang('form_description'));

        $this->crud->required_fields('code', 'description');
        $this->crud->fields('code', 'description');

        $this->crud_view = $this->crud->render();
        $this->load_page();
    }

    public function classroom() {
        $this->set_page_title('page_manage_classrooms');

        $this->crud->set_table('classroom');
        $this->crud->set_subject(lang('subject_classroom'));
        $this->crud->columns('id', 'center_id', 'capacity', 'notes', 'picture');
        $this->crud->display_as('id', lang('form_id'))
                ->display_as('center_id', lang('form_center'))
                ->display_as('capacity', lang('form_capacity'))
                ->display_as('notes', lang('form_notes'))
                ->display_as('picture', lang('form_photo'));

        $this->crud->required_fields('center_id', 'capacity');
        $this->crud->fields('center_id', 'capacity', 'notes', 'picture');
        $this->crud->set_field_upload('picture', 'assets/uploads/files/classroom');
        $this->crud->set_relation('center_id', 'center', 'name');

        $this->crud_view = $this->crud->render();
        $this->load_page();
    }

    public function teacher() {
        $this->set_page_title('page_manage_teachers');

        $this->crud->set_table('teacher');
        $this->crud->set_subject(lang('subject_teacher'));
        $this->crud->columns('contact_id', 'center', 'title', 'cv', 'type', 'start_date', 'end_date', 'state', 'bank_account_format', 'bank_account_number');
        $this->crud->display_as('contact_id', lang('form_name'))
                ->display_as('center', lang('form_center'))
                ->display_as('title', lang('form_title'))
                ->display_as('cv', lang('form_cv'))
                ->display_as('type', lang('form_type'))
                ->display_as('start_date', lang('form_start_date'))
                ->display_as('end_date', lang('form_end_date'))
                ->display_as('state', lang('form_state'))
                ->display_as('bank_account_format', lang('form_bank_account_format'))
                ->display_as('bank_account_number', lang('form_bank_account_number'));

        $this->crud->required_fields('contact_id', 'center', 'title', 'start_date');
        $this->crud->fields('contact_id', 'center', 'title', 'cv', 'type', 'start_date', 'end_date', 'state', 'bank_account_format', 'bank_account_number');
        $this->crud->field_type('start_date','date');
        $this->crud->field_type('end_date','date');
        $this->crud->set_relation('contact_id', 'contact', '{first_name} {last_name}');
        $this->crud->set_relation_n_n('center', 'teachers_by_centers', 'center', 'teacher_id', 'center_id', 'name');

        $this->crud_view = $this->crud->render();
        $this->load_page();
    }

    public function group() {
        $this->set_page_title('page_manage_groups');

        $this->crud->set_table('group');
        $this->crud->set_subject(lang('subject_group'));
        $this->crud->columns('id', 'name', 'center_id', 'classroom_id', 'teacher_id', 'level_code', 'academic_period', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'start_time', 'end_time');
        $this->crud->display_as('id', lang('form_id'))
                ->display_as('name', lang('form_name'))
                ->display_as('center_id', lang('form_center'))
                ->display_as('classroom_id', lang('form_classroom'))
                ->display_as('teacher_id', lang('form_teacher'))
                ->display_as('level_code', lang('form_level'))
                ->display_as('academic_period', lang('form_academic_period'))
                ->display_as('monday', lang('form_monday'))
                ->display_as('tuesday', lang('form_tuesday'))
                ->display_as('wednesday', lang('form_wednesday'))
                ->display_as('thursday', lang('form_thursday'))
                ->display_as('friday', lang('form_friday'))
                ->display_as('saturday', lang('form_saturday'))
                ->display_as('start_time', lang('form_start_time'))
                ->display_as('end_time', lang('form_end_time'));

        $this->crud->required_fields('name', 'center_id', 'classroom_id', 'teacher_id', 'level_code', 'academic_period', 'start_time');
        $this->crud->fields('name', 'center_id', 'classroom_id', 'teacher_id', 'level_code', 'academic_period', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'start_time', 'end_time');
        $this->crud->set_relation('center_id', 'center', 'name');
        $this->crud->set_relation('classroom_id', 'classroom', 'id');       
        $this->crud->field_type('monday','dropdown', array('0' => 'No', '1' => 'Si'));
        $this->crud->field_type('tuesday','dropdown', array('0' => 'No', '1' => 'Si'));
        $this->crud->field_type('wednesday','dropdown', array('0' => 'No', '1' => 'Si'));
        $this->crud->field_type('thursday','dropdown', array('0' => 'No', '1' => 'Si'));
        $this->crud->field_type('friday','dropdown', array('0' => 'No', '1' => 'Si'));
        $this->crud->field_type('saturday','dropdown', array('0' => 'No', '1' => 'Si'));
        $this->crud->set_primary_key('contact_id','view_teacher');
        $this->crud->set_relation('teacher_id', 'view_teacher', 'full_name');
        $this->crud->set_relation('level_code', 'level', 'description');
        $this->crud->set_relation('academic_period', 'academic_period', 'name');

        $this->crud_view = $this->crud->render();
        $this->load_page();
    }
    
    public function students_by_groups() {
        $this->set_page_title('page_manage_students_by_group');

        $this->crud->set_table('students_by_groups');
        $this->crud->set_subject(lang('subject_student'));
        $this->crud->columns('groups_id', 'student_id');
        $this->crud->display_as('groups_id', lang('subject_group'))
                ->display_as('student_id', lang('subject_student'));

        $this->crud->required_fields('groups_id', 'student_id');
        $this->crud->fields('groups_id', 'student_id');
        $this->crud->set_relation('groups_id', 'group', 'name');
        $this->crud->set_primary_key('contact_id','view_student');
        $this->crud->set_relation('student_id', 'view_student', 'full_name');       

        $this->crud_view = $this->crud->render();
        $this->load_page();
    }

    public function student() {
        $this->set_page_title('page_manage_students');

        $this->crud->set_table('student');
        $this->crud->set_subject(lang('subject_student'));
        $this->crud->columns('contact_id', 'center_id', 'start_date', 'end_date', 'school_academic_period', 'school_name', 'language_years', 'pref_start_time', 'pref_end_time', 'current_academic_period', 'bank_account_format', 'bank_account_number', 'bank_account_holder', 'bank_payment', 'current_level_code', 'leave_reason_code');
        $this->crud->display_as('contact_id', lang('form_name'))
                ->display_as('center_id', lang('form_center'))
                ->display_as('start_date', lang('form_start_date'))
                ->display_as('end_date', lang('form_end_date'))
                ->display_as('school_academic_period', lang('form_school_academic_period'))
                ->display_as('school_name', lang('form_school_name'))
                ->display_as('language_years', lang('form_language_years'))
                ->display_as('pref_start_time', lang('form_pref_start_time'))
                ->display_as('pref_end_time', lang('form_pref_end_time'))
                ->display_as('current_academic_period', lang('form_academic_period'))
                ->display_as('bank_account_format', lang('form_bank_account_format'))
                ->display_as('bank_account_number', lang('form_bank_account_number'))
                ->display_as('bank_account_holder', lang('form_bank_account_holder'))
                ->display_as('bank_payment', lang('form_bank_payment'))
                ->display_as('current_level_code', lang('form_level'))
                ->display_as('leave_reason_code', lang('form_leave_reason'));

        $this->crud->required_fields('contact_id', 'center_id', 'start_date', 'current_academic_period', 'current_level_code', 'group');
        $this->crud->fields('contact_id', 'center_id', 'start_date', 'end_date', 'school_academic_period', 'school_name', 'language_years', 'pref_start_time', 'pref_end_time', 'current_academic_period', 'bank_account_format', 'bank_account_number', 'bank_account_holder', 'bank_payment', 'current_level_code', 'leave_reason_code', 'group');
        $this->crud->field_type('start_date','date');
        $this->crud->field_type('end_date','date');
        $this->crud->set_relation('contact_id', 'contact', '{first_name} {last_name}');
        $this->crud->set_relation('center_id', 'center', 'name');
        $this->crud->set_relation('current_level_code', 'level', 'description');
        $this->crud->set_relation('leave_reason_code', 'leave_reason', 'description');
        $this->crud->set_relation('current_academic_period', 'academic_period', 'name');
        $this->crud->set_relation_n_n('group', 'students_by_groups', 'group', 'student_id', 'groups_id', 'name');

        $this->crud_view = $this->crud->render();
        $this->load_page();
    }

    public function qualification() {
        $this->set_page_title('page_manage_qualifications');

        $this->crud->set_table('qualification');
        $this->crud->set_subject(lang('subject_qualification'));
        $this->crud->columns('student_id', 'academic_period', 'description', 'qualification', 'trinity', 'london', 'others', 'eval1', 'eval2', 'eval3');
        $this->crud->display_as('student_id', lang('form_name'))
                ->display_as('academic_period', lang('form_academic_period'))
                ->display_as('description', lang('form_description'))
                ->display_as('qualification', lang('form_qualification'))
                ->display_as('trinity', lang('form_trinity'))
                ->display_as('london', lang('form_london'))
                ->display_as('others', lang('form_others'))
                ->display_as('eval1', lang('form_eval1'))
                ->display_as('eval2', lang('form_eval2'))
                ->display_as('eval3', lang('form_eval3'));

        $this->crud->required_fields('student_id', 'academic_period', 'qualification');
        $this->crud->fields('student_id', 'academic_period', 'description', 'qualification', 'trinity', 'london', 'others', 'eval1', 'eval2', 'eval3');
        $this->crud->set_relation('student_id', 'contact', '{first_name} {last_name}');
        $this->crud->set_relation('academic_period', 'academic_period', 'name');

        $this->crud_view = $this->crud->render();
        $this->load_page();
    }
    
    public function academic_period() {
        $this->set_page_title('page_manage_academic_period');

        $this->crud->set_table('academic_period');
        $this->crud->set_subject(lang('subject_academic_period'));
        $this->crud->columns('code', 'name');
        $this->crud->display_as('code', lang('form_id'))
                ->display_as('name', lang('form_name'));

        $this->crud->required_fields('code', 'name');
        $this->crud->fields('code', 'name');

        $this->crud_view = $this->crud->render();
        $this->load_page();
    }

}

/* End of file grocery_pages.php */
/* Location: ./application/controllers/admin_pages.php */
