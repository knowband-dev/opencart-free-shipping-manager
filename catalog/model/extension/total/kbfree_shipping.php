<?php

class ModelExtensionTotalKbFreeShipping extends Model {

    public function getTotal($total) {
        
        if ($this->config->get('total_kbfree_shipping_status') == 1) {

            if ($this->config->get('total_kbfree_shipping_voucher_status') == 1) {

                if (isset($this->session->data['coupon']) == FALSE) {
                    if ($this->cart->hasShipping()) {


                        $this->load->language('extension/shipping/kbfree_shipping');

                        $status = false;
                        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "kbfree_shipping_rules WHERE active = 1 ORDER BY priority");
                        $rules = $query->rows;

                        if (!empty($rules)) {

                            foreach ($rules as $result) {
                                $min_weight = $result['min_weight'];
                                $min_amount = $result['min_amount'];
                                $countries = explode(",", $result['excluded_country']);
                                if (($this->cart->getWeight() > $min_weight) && ($this->cart->getSubTotal() > $min_amount)) {
                                    $flag = false;
                                    if(!empty($this->session->data['shipping_address']['country_id'])) {
                                        foreach ($countries as $value) {
                                            if ($this->session->data['shipping_address']['country_id'] == $value) {
                                                $flag = true;
                                                break;
                                            }
                                        }
                                    }
                                    if ($flag != true) {
                                        $status = true;
                                        break;
                                    }
                                }
                            }
                        }

                        if ($status) {
                            $total['totals'][] = array(
                                'code' => 'kbfree_shipping',
                                'title' => $this->language->get('text_title'),
                                'value' => '-' . $this->session->data['shipping_method']['cost'],
                                'sort_order' => '7'
                            );

                            $total['total'] -= $this->session->data['shipping_method']['cost'];
                        }
                    }
                }
            } else {


                if ($this->cart->hasShipping()) {


                    $this->load->language('extension/shipping/kbfree_shipping');

                    $status = false;
                    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "kbfree_shipping_rules WHERE active = 1 ORDER BY priority");
                    $rules = $query->rows;

                    if (!empty($rules)) {

                        foreach ($rules as $result) {
                            $min_weight = $result['min_weight'];
                            $min_amount = $result['min_amount'];
                            $countries = explode(",", $result['excluded_country']);
                            if (($this->cart->getWeight() > $min_weight) && ($this->cart->getSubTotal() > $min_amount)) {
                                $flag = false;
                                if(!empty($this->session->data['shipping_address']['country_id'])) {
                                    foreach ($countries as $value) {
                                        if ($this->session->data['shipping_address']['country_id'] == $value) {
                                            $flag = true;
                                            break;
                                        }
                                    }
                                }
                                
                                if ($flag != true) {
                                    $status = true;
                                    break;
                                }
                            }
                        }
                    }

                    if ($status) {
                        $total['totals'][] = array(
                            'code' => 'kbfree_shipping',
                            'title' => $this->language->get('text_title'),
                            'value' => '-' . $this->session->data['shipping_method']['cost'],
                            'sort_order' => '7'
                        );

                        $total['total'] -= $this->session->data['shipping_method']['cost'];
                    }
                }
            }
        }
    }

    public function availableAmount() {

        if ($this->config->get('total_kbfree_shipping_status') == 1) {

            if ($this->config->get('total_kbfree_shipping_voucher_status') == 1) {

                if (isset($this->session->data['coupon']) == FALSE) {
                    if ($this->cart->hasShipping()) {


                        $this->load->language('extension/shipping/kbfree_shipping');

                        $status = false;
                        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "kbfree_shipping_rules WHERE active = 1 ORDER BY priority");
                        $rules = $query->rows;

                        $min = $this->db->query("SELECT min_amount FROM " . DB_PREFIX . "kbfree_shipping_rules WHERE active = 1 ORDER BY min_amount");
                        $min_amt = $min->rows[0];
                        if (!empty($rules)) {

                            foreach ($rules as $result) {
                                $min_weight = $result['min_weight'];
                                $min_amount = $result['min_amount'];
                                $countries = explode(",", $result['excluded_country']);
                                $flag = false;
                                if(!empty($this->session->data['shipping_address']['country_id'])) {
                                    foreach ($countries as $value) {
                                        if ($this->session->data['shipping_address']['country_id'] == $value) {
                                            $flag = true;
                                            break;
                                        }
                                    }
                                }
                                if ($flag != true) {
                                    $status = true;
                                    break;
                                }
                            }
                        }
                        if ($status) {
                            return $min->rows[0];
                        }
                    }
                }
            } else {

                if ($this->cart->hasShipping()) {

                    $this->load->language('extension/shipping/kbfree_shipping');

                    $status = false;
                    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "kbfree_shipping_rules WHERE active = 1 ORDER BY priority");
                    $rules = $query->rows;

                    $min = $this->db->query("SELECT min_amount FROM " . DB_PREFIX . "kbfree_shipping_rules WHERE active = 1 ORDER BY min_amount");
                    $min_amt = $min->rows[0];

                    if (!empty($rules)) {

                        foreach ($rules as $result) {
                            $min_weight = $result['min_weight'];
                            $min_amount = $result['min_amount'];
                            $countries = explode(",", $result['excluded_country']);
                            $flag = false;
                            if(!empty($this->session->data['shipping_address']['country_id'])) {
                                foreach ($countries as $value) {
                                    if ($this->session->data['shipping_address']['country_id'] == $value) {
                                        $flag = true;
                                        break;
                                    }
                                }
                            }
                            if ($flag != true) {
                                $status = true;
                                break;
                            }
                        }
                    }

                    if ($status) {
                        return $min->rows[0];
                    }
                }
            }
        }
    }

}
