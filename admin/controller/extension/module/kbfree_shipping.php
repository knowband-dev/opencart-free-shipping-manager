<?php

class ControllerExtensionModuleKbfreeshipping extends Controller {

    private $error = array();
    private $session_token_key = 'token';
    private $session_token = '';
    private $module_path = '';

    public function __construct($registry)
    {
        parent::__construct($registry);
        if (VERSION >= 3.0) {
            $this->session_token_key = 'user_token';
            $this->session_token = $this->session->data['user_token'];
        } else {
            $this->session_token_key = 'token';
            $this->session_token = $this->session->data['token'];
        }
        if (VERSION <= '2.2.0') {
            $this->module_path = 'module';
        } else {
            $this->module_path = 'extension/module';
        }
        //echo $this->module_path;
    }    

    
    public function index() {
        
        $this->load->language($this->module_path.'/kbfree_shipping');
        
        $this->load->model('setting/setting');
        $this->load->model('setting/kbfree_shipping');

        $this->document->addScript(HTTPS_SERVER . 'view/javascript/kbfree_shipping/jquery.colorpicker.js');
        
        $store_id = 0;
        if (isset($this->request->get['store_id'])) {
            $store_id = $this->request->get['store_id'];
        }

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {

            $this->model_setting_setting->editSetting('total_kbfree_shipping', $this->request->post, $store_id);
            
              $info = $this->model_setting_kbfree_shipping->ex_value();

            if(empty($info)){

                if($this->request->post['total_kbfree_shipping_status'] == '1'){
                    $this->model_setting_kbfree_shipping->addExtension();
                }
            }
			
			$enable_status['module_kbfree_shipping_status'] = $this->request->post['total_kbfree_shipping_status'];
            $this->model_setting_setting->editSetting('module_kbfree_shipping', $enable_status, $store_id);

            $data['success'] = $this->language->get('text_success');
        }

        
        $kbfree_shipping = $this->model_setting_setting->getSetting('total_kbfree_shipping', $store_id); 

        $data['action'] = $this->url->link($this->module_path.'/kbfree_shipping', $this->session_token_key.'=' . $this->session_token . "&store_id=" . $store_id, true);
        $data['cancel'] = $this->url->link('extension/extension', $this->session_token_key.'=' . $this->session_token . '&store_id=' . $store_id, true);
        $data['text_edit'] = $this->language->get('text_edit');
        $data['heading_title'] = $this->language->get('heading_title');
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', $this->session_token_key.'=' . $this->session_token . '&store_id=' . $store_id, true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('extension/extension', $this->session_token_key.'=' . $this->session_token . '&type=module&store_id=' . $store_id, true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title_main'),
            'href' => $this->url->link($this->module_path.'/kbfree_shipping', $this->session_token_key.'=' . $this->session_token . '&store_id=' . $store_id, true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('tab_module_configuration'), 
            'href' => $this->url->link($this->module_path.'/kbfree_shipping', $this->session_token_key.'=' . $this->session_token . '&store_id=' . $store_id, true)
        );

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');
        
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        
        $data['entry_status'] = $this->language->get('entry_status');
        $data['voucher_status'] = $this->language->get('voucher_status');
        $data['free_shipping_list'] = $this->language->get('free_shipping_list');
        $data['background_color'] = $this->language->get('background_color');
        $data['font_color'] = $this->language->get('font_color');  
        $data['border_color'] = $this->language->get('border_color'); 
        $data['custom_css'] = $this->language->get('custom_css'); 
        $data['hint_status'] = $this->language->get('hint_status');
        $data['hint_free_shipping_list'] = $this->language->get('hint_free_shipping_list');
        $data['hint_background_color'] = $this->language->get('hint_background_color');
        $data['hint_font_color'] = $this->language->get('hint_font_color');
        $data['hint_border_color'] = $this->language->get('hint_border_color');
        $data['hint_custom_css'] = $this->language->get('hint_custom_css');
        
        $data['default_voucher_status_description'] = $this->language->get('default_voucher_status_description');
        $data['default_custom_css_description'] = $this->language->get('default_custom_css_description');
        
        $data['tab_module_configuration'] = $this->language->get('tab_module_configuration');
        $data['error_field_empty']= $this->language->get('error_field_empty');       

       if (isset($this->request->post['total_kbfree_shipping_status'])) {
            $data['total_kbfree_shipping_status'] = $this->request->post['total_kbfree_shipping_status'];
        } elseif (!empty($kbfree_shipping) && isset($kbfree_shipping['total_kbfree_shipping_status'])) {
            $data['total_kbfree_shipping_status'] = $kbfree_shipping['total_kbfree_shipping_status'];
        } else {
            $data['total_kbfree_shipping_status'] = '0';
        }
        if (isset($this->request->post['total_kbfree_shipping_voucher_status'])) {
            $data['total_shipping_voucher_status'] = $this->request->post['total_kbfree_shipping_voucher_status'];
        } elseif (!empty($kbfree_shipping) && isset($kbfree_shipping['total_kbfree_shipping_voucher_status'])) {
            $data['total_kbfree_shipping_voucher_status'] = $kbfree_shipping['total_kbfree_shipping_voucher_status'];
        } else {
            $data['total_kbfree_shipping_voucher_status'] = '0';
        }
        if (isset($this->request->post['kbfree_shipping_free_shipping_list'])) {
            $data['total_kbfree_shipping_free_shipping_list'] = $this->request->post['total_kbfree_shipping_free_shipping_list'];
        } elseif (!empty($kbfree_shipping) && isset($kbfree_shipping['total_kbfree_shipping_free_shipping_list'])) {
            $data['total_kbfree_shipping_free_shipping_list'] = $kbfree_shipping['total_kbfree_shipping_free_shipping_list'];
        } else {
            $data['total_kbfree_shipping_free_shipping_list'] = '0';
        }
        if (isset($this->request->post['total_kbfree_shipping_backgroundcolor'])) {
            $data['total_kbfree_shipping_background_color'] = $this->request->post['total_kbfree_shipping_backgroundcolor'];
        } elseif (!empty($kbfree_shipping)  && isset($kbfree_shipping['total_kbfree_shipping_backgroundcolor'])) {
            $data['total_kbfree_shipping_background_color'] = $kbfree_shipping['total_kbfree_shipping_backgroundcolor'];
        } else {
            $data['total_kbfree_shipping_background_color'] = "#43b155";
        }
        if (isset($this->request->post['total_kbfree_shipping_fontcolor'])) {
            $data['total_kbfree_shipping_font_color'] = $this->request->post['total_kbfree_shipping_fontcolor'];
        } elseif (!empty($kbfree_shipping) && isset($kbfree_shipping['total_kbfree_shipping_fontcolor'])) {
            $data['total_kbfree_shipping_font_color'] = $kbfree_shipping['total_kbfree_shipping_fontcolor'];
        } else {
            $data['total_kbfree_shipping_font_color'] = "#ffffff";
        }
        if (isset($this->request->post['total_kbfree_shipping_bordercolor'])) {
            $data['total_kbfree_shipping_border_color'] = $this->request->post['total_kbfree_shipping_bordercolor'];
        } elseif (!empty($kbfree_shipping)  && isset($kbfree_shipping['total_kbfree_shipping_bordercolor'])) {
            $data['total_kbfree_shipping_border_color'] = $kbfree_shipping['total_kbfree_shipping_bordercolor'];
        } else {
            $data['total_kbfree_shipping_border_color'] = "#43b155";
        }
        if (isset($this->request->post['total_kbfree_shipping_custom_css'])) {
            $data['total_kbfree_shipping_custom_css'] = $this->request->post['total_kbfree_shipping_custom_css'];
        } elseif (!empty($kbfree_shipping)  && isset($kbfree_shipping['total_kbfree_shipping_custom_css'])) {
            $data['total_kbfree_shipping_custom_css'] = $kbfree_shipping['total_kbfree_shipping_custom_css'];
        } else {
            $data['total_kbfree_shipping_custom_css'] = "";
        }
        
        $active_tab['active'] = 1;
        $data['tab_common'] = $this->load->controller($this->module_path.'/kbfree_shipping/tabs', $active_tab);

        $data_swticher['current_url'] = $this->url->link($this->module_path.'/kbfree_shipping', $this->session_token_key.'=' . $this->session_token, true);
        $data_swticher['store_id'] = $store_id;
        $data['storeSwticher'] = $this->load->controller($this->module_path.'/kbfree_shipping/storeSwticher', $data_swticher);
        
        //title for kbfree_shipping page 
        $this->document->setTitle($this->language->get('heading_title_main'));
        
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        
         if (VERSION < '2.2.0') {
            $this->response->setOutput($this->load->view($this->module_path.'/kbfree_shipping/kbfree_shipping.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view($this->module_path.'/kbfree_shipping/kbfree_shipping', $data));
        }
       
    }

    public function tabs($active_tab) {
        $this->load->language($this->module_path.'/kbfree_shipping');
        $store_id = 0;
        if (isset($this->request->get['store_id'])) {
            $store_id = $this->request->get['store_id'];
        }
        $data['active'] = $active_tab['active'];
        $data['tab_module_configuration'] = $this->language->get('tab_module_configuration');
        $data['tab_free_shipping_rule'] = $this->language->get('tab_free_shipping_rule');

        $data['configuration'] = $this->url->link($this->module_path.'/kbfree_shipping', $this->session_token_key.'=' . $this->session_token . '&store_id=' . $store_id, true);
        $data['rule'] = $this->url->link($this->module_path.'/kbfree_shipping/rule', $this->session_token_key.'=' . $this->session_token . '&store_id=' . $store_id, true);
        
        if (VERSION < '2.2.0') {
            return $this->load->view($this->module_path.'/kbfree_shipping/tabs.tpl', $data);
        } else {
             return $this->load->view($this->module_path.'/kbfree_shipping/tabs', $data);
        }
    }

    public function rule() {

        $store_id = 0;
        if (isset($this->request->get['store_id'])) {
            $store_id = $this->request->get['store_id'];
        }
        //page no.
        if (!isset($this->request->get['page'])) {
            $page = 1;
        }
        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        }
        
        $this->load->language($this->module_path.'/kbfree_shipping');
        $this->load->model('setting/kbfree_shipping');
        $this->load->model('localisation/weight_class');
        $this->load->model('localisation/currency');

        $data['action'] = $this->url->link($this->module_path.'/kbfree_shipping', $this->session_token_key.'=' . $this->session_token . '&store_id=' . $store_id, true);
        $data['cancel'] = $this->url->link('extension/extension', $this->session_token_key.'=' . $this->session_token . '&store_id=' . $store_id, true);
        $data['text_edit'] = $this->language->get('text_edit');
        $data['heading_title'] = $this->language->get('heading_title');
        
        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        }
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', $this->session_token_key.'=' . $this->session_token . '&store_id=' . $store_id, true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('extension/extension', $this->session_token_key.'=' . $this->session_token . '&type=module&store_id=' . $store_id, true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title_main'),
            'href' => $this->url->link($this->module_path.'/kbfree_shipping', $this->session_token_key.'=' . $this->session_token . '&store_id=' . $store_id, true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('tab_free_shipping_rule'),
            'href' => $this->url->link($this->module_path.'/kbfree_shipping/rule', $this->session_token_key.'=' . $this->session_token . '&store_id=' . $store_id, true)
        );

       //Default weight
        $filter_data = array();
        $weights = $this->model_localisation_weight_class->getWeightClasses($filter_data);
        
        foreach ($weights as $result) {
            
            if($result['weight_class_id'] == $this->config->get('config_weight_class_id')){
                
                $data['default_weight_value'] = $result['unit'];
            }
	}
        
        //Default currency
        $currencies = $this->model_localisation_currency->getCurrencies($filter_data);
        
        foreach ($currencies as $result) {
            if($result['code'] == $this->config->get('config_currency')){
                
                if($result['symbol_left'] != ''){
                    $data['default_currency_value'] = $result['symbol_left'];
                } elseif($result['symbol_right'] != ''){
                    $data['default_currency_value'] = $result['symbol_right'];
                }
            }
	}
        
        //pagination 
        
        if (!isset($this->request->get['page'])) {
            $page = 1;
        }
        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        }
        $last = $this->config->get('config_limit_admin');//2;
        
        $filter_data = array(
            'start' => ($page - 1) * $last,
            'limit' => $last
        );
		//fetching details of product with pagination and new methods created in kbfree_shipping
        $total_rules = $this->model_setting_kbfree_shipping->gettotalRule($store_id);
        $results = $this->model_setting_kbfree_shipping->getRule($filter_data,$store_id);
    
	$pagination = new Pagination();
        $pagination->total = $total_rules;
        $pagination->page = $page;
        $pagination->limit = $last;
        $pagination->url = $this->url->link($this->module_path . '/kbfree_shipping/rule', $this->session_token_key . '=' . $this->session_token . '&page={page}', true);
        $data['pagination'] = $pagination->render();
        $data['results'] = sprintf($this->language->get('text_pagination'), ($total_rules) ? (($page - 1) * $pagination->limit) + 1 : 0, ((($page - 1) * $pagination->limit) > ($total_rules - $pagination->limit)) ? $total_rules : ((($page - 1) * $pagination->limit) + $pagination->limit), $total_rules, ceil($total_rules / $pagination->limit));
          
        foreach ($results as $result) {

            $data['shipping_rules'][] = array(
                'id' => $result['id'],
                'name' => $result['name'],
                'min_amount' => $result['min_amount'],
                'min_weight' => $result['min_weight'],
                'priority' => $result['priority'],
                'active' => $result['active'],
                'date_modified' => date('d-m-Y', strtotime($result['date_modified'])),
                'edit' => $this->url->link($this->module_path.'/kbfree_shipping/updaterule', $this->session_token_key.'=' . $this->session_token . '&id=' . $result['id'] . '&store_id=' . $store_id, true),
                'delete' => $this->url->link($this->module_path.'/kbfree_shipping/deleterule', $this->session_token_key.'=' . $this->session_token . '&id=' . $result['id'] . '&store_id=' . $store_id, true),
            );
        }
        //title for kbbloker page 
        $this->document->setTitle($this->language->get('heading_title_main_free_shipping_rule'));
       
        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['tab_free_shipping_rule'] = $this->language->get('tab_free_shipping_rule');
        $data['id'] = $this->language->get('id');
        $data['min_amount'] = $this->language->get('min_amount');
        $data['min_weight'] = $this->language->get('min_weight');
        $data['priority'] = $this->language->get('priority');
        $data['active'] = $this->language->get('active');
        $data['name'] = $this->language->get('name');
        $data['last_update'] = $this->language->get('last_update');
        $data['action_on_rules'] = $this->language->get('action');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['button_add_new'] = $this->language->get('button_add_new');
        $data['button_edit'] = $this->language->get('button_edit');
        $data['button_delete'] = $this->language->get('button_delete');
        $data['no_result_message'] = $this->language->get('no_result_message');
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $data['add_new'] = $this->url->link($this->module_path.'/kbfree_shipping/updaterule', $this->session_token_key.'=' . $this->session_token . '&store_id=' . $store_id, true);
        $active_tab['active'] = 2;
        $data['tab_common'] = $this->load->controller($this->module_path.'/kbfree_shipping/tabs', $active_tab);
        $data_swticher['current_url'] = $this->url->link($this->module_path.'/kbfree_shipping/rule', $this->session_token_key.'=' . $this->session_token , true);
        $data_swticher['store_id'] = $store_id;
        $data['storeSwticher'] = $this->load->controller($this->module_path.'/kbfree_shipping/storeSwticher', $data_swticher);
        
        
        if (VERSION < '2.2.0') {
        $this->response->setOutput($this->load->view($this->module_path.'/kbfree_shipping/rule.tpl', $data)); 
         } else {
         $this->response->setOutput($this->load->view($this->module_path.'/kbfree_shipping/rule', $data)); 
         }
    }

    public function deleterule() {
        $store_id = 0;
        if (isset($this->request->get['store_id'])) {
            $store_id = $this->request->get['store_id'];
        }
        $this->load->model('setting/kbfree_shipping');
        $this->load->language($this->module_path.'/kbfree_shipping');
       
            
        $this->model_setting_kbfree_shipping->deleteRule($this->request->get['id']);
        $this->session->data['success'] = $this->language->get('free_shipping_rule_delete_successfully');
        $this->response->redirect($this->url->link($this->module_path.'/kbfree_shipping/rule', $this->session_token_key.'=' . $this->session_token . '&store_id=' . $store_id, true));
    }

    public function updaterule() {
        
        $this->load->language($this->module_path.'/kbfree_shipping');
        $this->load->model('setting/kbfree_shipping');
        $this->load->model('localisation/country');    
        $this->load->model('localisation/weight_class');
        $this->load->model('localisation/currency');
        
        $store_id = 0;
        if (isset($this->request->get['store_id'])) {
            $store_id = $this->request->get['store_id'];
        }

//        print_r($this->request->post);        die();
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
//        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if (isset($this->request->get['id'])) {
                    $this->model_setting_kbfree_shipping->updateRule($this->request->post, $this->request->get['id']);
                    $this->session->data['success'] = $this->language->get('free_shipping_rule_update_successfully');
                    $this->response->redirect($this->url->link($this->module_path.'/kbfree_shipping/rule', $this->session_token_key.'=' . $this->session_token . '&store_id=' . $store_id, true));      
           } else {
                    $this->model_setting_kbfree_shipping->addRule($this->request->post, $store_id);
                    $this->session->data['success'] = $this->language->get('free_shipping_rule_add_successfully');
                    $this->response->redirect($this->url->link($this->module_path.'/kbfree_shipping/rule', $this->session_token_key.'=' . $this->session_token . '&store_id=' . $store_id, true));
            }
        }
        
        //country names exploding when edit functionality functions
        if (isset($this->request->get['id'])) {
            $rule_info = $this->model_setting_kbfree_shipping->editRuleinfo($this->request->get['id']);
            
            $country_edit = array();
            $country_edit = explode(",",$rule_info['excluded_country']);
        }
        //Default weight
        $filter_data = array();
        $weights = $this->model_localisation_weight_class->getWeightClasses($filter_data);
        
        foreach ($weights as $result) {
            
            if($result['weight_class_id'] == $this->config->get('config_weight_class_id')){
                
                $data['default_weight_value'] = $result['unit'];
            }
	}
        
        //Default currency
        $currencies = $this->model_localisation_currency->getCurrencies($filter_data);
        
        foreach ($currencies as $result) {
            if($result['code'] == $this->config->get('config_currency')){
                
                if($result['symbol_left'] != ''){
                    $data['default_currency_value'] = $result['symbol_left'];
                } elseif($result['symbol_right'] != ''){
                    $data['default_currency_value'] = $result['symbol_right'];
                }
            }
	}
        
        $data['action'] = $this->url->link($this->module_path.'/kbfree_shipping/getFormfree_shipping', $this->session_token_key.'=' . $this->session_token . '&store_id=' . $store_id, true);
        $data['cancel'] = $this->url->link($this->module_path.'/kbfree_shipping/rule', $this->session_token_key.'=' . $this->session_token . '&store_id=' . $store_id, true);
        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['hint_min_amount'] = $this->language->get('hint_min_amount');
        $data['hint_min_weight'] = $this->language->get('hint_min_weight');
        $data['hint_country'] = $this->language->get('hint_country');
        $data['text_none_country'] = $this->language->get('text_none_country');
        $data['default_min_amount_description'] = $this->language->get('default_min_amount_description');
        $data['default_min_weight_description'] = $this->language->get('default_min_weight_description');
        
        $data['heading_title'] = $this->language->get('heading_title');
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', $this->session_token_key.'=' . $this->session_token . '&store_id=' . $store_id, true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('extension/extension', $this->session_token_key.'=' . $this->session_token . '&store_id=' . $store_id . '&type=module', true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title_main'),
            'href' => $this->url->link($this->module_path.'/kbfree_shipping', $this->session_token_key.'=' . $this->session_token . '&store_id=' . $store_id, true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('tab_free_shipping_rule'),
            'href' => $this->url->link($this->module_path.'/kbfree_shipping/rule', $this->session_token_key.'=' . $this->session_token . '&store_id=' . $store_id, true)
        );
        if (isset($this->request->get['id'])) {
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('edit_rule'),
                'href' => $this->url->link($this->module_path.'/kbfree_shipping/updaterule', $this->session_token_key.'=' . $this->session_token . '&store_id=' . $store_id . '&id=' . $this->request->get['id'], true)
            );
            $data['text_edit'] = $this->language->get('edit_rule');
        } else {
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('add_rule'),
                'href' => $this->url->link($this->module_path.'/kbfree_shipping/updaterule', $this->session_token_key.'=' . $this->session_token . '&store_id=' . $store_id, true)
            );
            $data['text_edit'] = $this->language->get('add_rule');
        }
        $data['tab_module_configuration'] = $this->language->get('tab_module_configuration');
        $data['tab_free_shipping_rule'] = $this->language->get('tab_free_shipping_rule');
        $data['entry_name'] = $this->language->get('name');
        $data['id'] = $this->language->get('id');
        $data['entry_group_name'] = $this->language->get('group_name');
        $data['entry_min_amount'] = $this->language->get('min_amount');
        $data['entry_min_weight'] = $this->language->get('min_weight');
        $data['entry_priority'] = $this->language->get('priority');
        $data['entry_active'] = $this->language->get('active');
        $data['entry_country'] = $this->language->get('country');
        $data['entry_entry_status'] = $this->language->get('entry_status');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['error_field_empty']=$this->language->get('error_field_empty');
	$data['error_text_amount_valid']=$this->language->get('error_text_amount_valid');
        $data['error_text_amount_positive']=$this->language->get('error_text_amount_positive');
        $data['error_text_priority_numeric']=$this->language->get('error_text_priority_numeric');
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        
        $data['countries'] = $this->model_localisation_country->getCountries();
        
        if (isset($this->error['priority'])) {
            $data['error_priority'] = $this->error['priority'];
        } else {
            $data['error_priority'] = '';
        }
        
        if (isset($this->request->post['name'])) {
            $data['name'] = $this->request->post['name'];
        } elseif (isset($rule_info)) {
            $data['name'] = $rule_info['name'];
        } else {
            $data['name'] = '';
        }
        if (isset($this->request->post['min_amount'])) {
            $data['min_amount'] = $this->request->post['min_amount'];
        } elseif (isset($rule_info)) {
            $data['min_amount'] = $rule_info['min_amount'];
        } else {
            $data['min_amount'] = '';
        }
        if (isset($this->request->post['min_weight'])) {
            $data['min_weight'] = $this->request->post['min_weight'];
        } elseif (isset($rule_info)) {
            $data['min_weight'] = $rule_info['min_weight'];
        } else {
            $data['min_weight'] = '';
        }
        if (isset($this->request->post['priority'])) {
            $data['priority'] = $this->request->post['priority']; 
        } elseif (isset($rule_info)) {
            $data['priority'] = $rule_info['priority'];
        } else {
            $data['priority'] = '';
        }
        if (isset($this->request->post['active'])) {
            $data['active'] = $this->request->post['active'];
        } elseif (isset($rule_info)) {
            $data['active'] = $rule_info['active'];
        } else {
            $data['active'] = '1';
        }
        if (isset($this->request->post['excluded_country'])) {
            $data['excluded_country'] = $this->request->post['excluded_country'];  
        } elseif (isset($rule_info)) {
            $data['excluded_country'] = $country_edit;
        } else {
            $data['excluded_country'] = '';
        }
        
        //print_r($data['excluded_country']); die();
        $active_tab['active'] = 2;
        $data['tab_common'] = $this->load->controller($this->module_path.'/kbfree_shipping/tabs', $active_tab);
        if (isset($this->request->get['id'])) {
        if (VERSION < '2.2.0') {
        $this->response->setOutput($this->load->view($this->module_path.'/kbfree_shipping/ruleform_update.tpl', $data));
        } else {
        $this->response->setOutput($this->load->view($this->module_path.'/kbfree_shipping/ruleform_update', $data));    
        }
        }
        else{
            if (VERSION < '2.2.0') {
        $this->response->setOutput($this->load->view($this->module_path.'/kbfree_shipping/ruleform.tpl', $data));
        } else {
        $this->response->setOutput($this->load->view($this->module_path.'/kbfree_shipping/ruleform', $data));    
        }
        }
    }

    public function validate()
    {
        $this->load->language($this->module_path.'/kbfree_shipping');
        $this->load->model('setting/kbfree_shipping');
        $priority_info = $this->model_setting_kbfree_shipping->priorityCheck();
        $p_no = $this->request->post['priority'];
        $count = 0;
        
        foreach ($priority_info as $value) {
            $pr = $value['priority'];
            if($p_no == $pr){
                $count++;
            }
        }
        if(isset($this->request->get['id'])){
            $id = $this->request->get['id'];
            if($count == 1){

                $priority_infoo = $this->model_setting_kbfree_shipping->priorityatidCheck($p_no,$id);

                if (empty($priority_infoo)) {
                    $status = 1;
                } else {
                    $status = 0;
                }

                if($status == 1){
                    $this->error['priority'] = $this->language->get('error_text_priority');
                }
            }
        }
        else{
            if($count > 0){
                $this->error['priority'] = $this->language->get('error_text_priority');
            }
        }
        return !$this->error; 
    }
    
    public function install() {
        
       
        $create_free_shipping_rule = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "kbfree_shipping_rules` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(30) NOT NULL,
            `min_amount` int(11) NOT NULL,
            `min_weight` int(11) NOT NULL,
            `priority` int(11) NOT NULL,
            `active` varchar(3) NOT NULL,
            `excluded_country` text NOT NULL,      
            `store_id` int(11) NOT NULL,
            `date_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1";

        
        $this->db->query($create_free_shipping_rule);
    }

    public function storeSwticher($data_switcher) {
        $this->load->language($this->module_path.'/kbfree_shipping');
        $this->load->model('setting/store');
        $stores = $this->model_setting_store->getStores();
        if (!empty($stores)) {
            foreach ($stores as $result) {
                $data['all_store'][] = array(
                    'id' => $result['store_id'],
                    'name' => $result['name'],
                );
            }
            $data['current_url'] = $data_switcher['current_url'];
            $data['store_id'] = $data_switcher['store_id'];
            $data['default'] = $this->language->get('default');
            if (VERSION < '2.2.0') {
            return $this->load->view($this->module_path.'/kbfree_shipping/storeswticher.tpl', $data);
            } else {
             return $this->load->view($this->module_path.'/kbfree_shipping/storeswticher', $data);   
            }
        }
    }

}
