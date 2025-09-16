<?php
class ModelSettingKbfreeshipping extends Model {

    public function ex_value(){
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE type = 'total' AND code = 'kbfree_shipping'");

        return $query->rows;
    }
    
    public function addExtension(){
        $this->db->query("INSERT INTO " . DB_PREFIX . "extension SET type = 'total', code = 'kbfree_shipping'");
    }
    
    //for pagination
    public function getRule($filter,$store_id)
    {
        $data = array();
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "kbfree_shipping_rules WHERE store_id =" . (int)$store_id." order by date_modified DESC LIMIT " . (int) $filter['start'] . "," . (int) $filter['limit']);
        foreach ($query->rows as $result) {
            $data[] = $result;
        }
        return $data;
    }
    //for pagination
    public function gettotalRule($store_id)
    {
        $sql = "SELECT COUNT(DISTINCT id) AS total FROM " . DB_PREFIX . "kbfree_shipping_rules WHERE store_id =" . (int)$store_id;
        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function editRuleinfo($id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "kbfree_shipping_rules WHERE id =" . (int)$id);
        return $query->rows[0];
    }
    
    public function addRule($data, $store_id) {
        
        $str_country = implode(",", $data['excluded_country']);
        $this->db->query("INSERT INTO " . DB_PREFIX . "kbfree_shipping_rules SET name = '" . $this->db->escape($data['name']) . "', min_amount = '" . (int)$data['min_amount'] . "', min_weight = '" . (int)$data['min_weight'] . "', priority = '" . (int)$data['priority'] . "', active = '" . $this->db->escape($data['active']) . "', excluded_country = '" . $str_country . "', store_id = '" . (int)$store_id . "'");
        
    }

    public function priorityCheck() {
        $query = $this->db->query("SELECT priority FROM " . DB_PREFIX . "kbfree_shipping_rules");
        return $query->rows;
    }
    
    public function priorityatidCheck($priority,$id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "kbfree_shipping_rules WHERE priority =" . (int)$priority ." AND id =" . (int)$id . "" );
        return $query->rows;
    }
    
    public function updateRule($data, $id) {
        
        $str_country = implode(",", $data['excluded_country']);
        $this->db->query("UPDATE " . DB_PREFIX . "kbfree_shipping_rules SET name = '" . $this->db->escape($data['name']) . "', min_amount = '" . (int)$data['min_amount'] . "', min_weight = '" . (int)$data['min_weight'] . "', priority = '" . (int)$data['priority'] . "', active = '" . $this->db->escape($data['active']) . "', excluded_country = '" . $str_country . "' WHERE id = '" . (int)$id . "'");
    }

    public function deleteRule($id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "kbfree_shipping_rules WHERE id ='" . (int)$id . "'");
    }

}

?>