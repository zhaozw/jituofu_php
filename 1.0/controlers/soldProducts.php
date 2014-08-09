<?php
include_once('config/config.php');
include_once($libs_dir.'/db.php');
require_once("models/config.php");

$user_id = null;
if(isset($loggedInUser) && $loggedInUser->user_id){
    $user_id = $loggedInUser->user_id;
}
if(!$user_id){
    $result = array("bizCode" => 0, "memo" => "用户未登录", "data"=>array("redirect"=>"login.html"));
    echo json_encode($result);
    exit;
}
if($client_action === "query"){
    $date = $_POST['date'];
    if(!$date){
        $result = array("bizCode" => 0, "memo" => "没有传入日期参数", "data"=>array());
        echo json_encode($result);
        exit;
    }

    $db = new DB($db_name,$db_host,$db_username,$db_password);
    $db->query("SET NAMES 'UTF8'");

    $where = "((user_id=$user_id) and (date like '{$date}%'";
    $where .= '))';

    $query_sold_sql = "select pid,selling_count,date,id,selling_price from `cashier` where $where ORDER BY date DESC";
    $sold_data = $db->queryManyObject($query_sold_sql);

    $ids = array();
    foreach($sold_data as $k => $v){
        array_push($ids, $v->pid);
    }
    $ids = array_unique($ids);

    $where = "((user_id=$user_id) and (";
    foreach($ids as $k => $v){
        if($k !== 0){
            $where .= ' or ';
        }
        $where .= "id='$v'";
    }
    $where .= '))';

    if(count($ids) === 0){
        $where = "(user_id=$user_id)";
    }

    $query_price_sql = "select price,id,type,pic,name from `products` where $where ORDER BY date DESC";
    $query_price_data = $db->queryManyObject($query_price_sql);
    $operation = array();
    $types = array();
    foreach($sold_data as $kk => $vv){
        foreach($query_price_data as $k => $v){
            if($vv-> pid == $v -> id){
                $t = $v -> type;
                $query_type_name_sql = "select name from types where (id=$t)";
                $type = $db->queryObject($query_type_name_sql);

                if(!$type){
                    $type = new stdClass();
                    $type -> name = "未知分类";
                }
                $selling_count = $vv -> selling_count;
                $selling_price = $vv -> selling_price;
                array_push(
                    $operation,
                    array('p_id' => $v-> id, 'detail' => "$selling_price*$selling_count|", 'p_price' => $v->price,'date'=>$vv->date, 'type'=>$type->name, 'order_id'=>$vv->id, 'prop'=>"", 'p_pic'=>$v->pic,'p_name'=>$v->name)
                );
            }
        }
    }
    $db->close();

    $result = array("bizCode" => 1, "memo" => "", "data"=>array("products" => $operation));
    echo json_encode($result);
    exit;
}
?>