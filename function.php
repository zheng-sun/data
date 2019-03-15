<?php

// 运行 sql
function query($sql, $link) {
    if ($link) {
        $resource = mysql_query($sql, $link);

        if ($resource) {
            if (is_resource($resource)) {
                $i = 0;
        
                $data = array();
        
                while ($result = mysql_fetch_assoc($resource)) {
                    $data[$i] = $result;
        
                    $i++;
                }
                
                mysql_free_result($resource);
                
                $query = new stdClass();
                $query->row = isset($data[0]) ? $data[0] : array();
                $query->rows = $data;
                $query->num_rows = $i;
                
                unset($data);
                
                return $query;  
            } else {
                return true;
            }
        } else {
            trigger_error('Error: ' . mysql_error($link) . '<br />Error No: ' . mysql_errno($link) . '<br />' . $sql."<br>".$_SERVER['REQUEST_URI']);
            exit();
        }
    }
}
?>