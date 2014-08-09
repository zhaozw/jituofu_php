<?php
include_once('config/config.php');
include_once($libs_dir.'/db.php');
require_once("models/config.php");

$user_id = null;
if(isset($loggedInUser) && $loggedInUser->user_id){
    $user_id = $loggedInUser->user_id;
}
if(!$user_id){
    $result = array("bizCode" => 0, "memo" => "用户未登录", "data"=>array("redirect"=>"login.php"));
    echo json_encode($result);
    exit;
}

$db = new DB($db_name,$db_host,$db_username,$db_password);
$db->query("SET NAMES 'UTF8'");

if($client_action === "query"){
    $page_num = @$_POST['pageNum'];
    if(!$page_num){
        $page_num = 1;
    }
    $limit_start = 0;
    $limit = @$_POST['limit'];
    $type = @$_POST['type'];
    $count = @$_POST['count'];
    $name = @$_POST['name'];
    if($count){
        $count_condition = "(count <= $count)";
    }
    if(!$limit){
        $limit = 10;
    }
    if(!$type){
        $type = 0;
    }
    $limit_end = (int)$limit;
    $limit_start = (int)$limit*((int)$page_num-1);
    $where = "((status=1) and user_id=$user_id)";
    if(isset($count_condition)){
        $where .= ' and '.$count_condition;
    }

    if($name){
        $where .= " and (name like '%$name%')";

    }
    if($type){
        $where .= " and (type=$type)";
    }
    $sql = "select * from `products` where ($where) ORDER BY date DESC limit $limit_start,$limit_end";
    $data = $db->queryManyObject($sql);
    $db->close();

    if(isset($_POST['ajax'])){
        $results = array();
        foreach($data as $k=>$v){
            $result = array();
            $result['p_id'] = $v->id;
            $result['user_id'] = $v->user_id;
            $result['p_name'] = $v->name;
            $result['p_count'] = $v->count;
            $result['p_price'] = $v->price;
            $result['p_from'] = $v->from;
            $result['p_man'] = $v->man;
            $result['p_pic'] = $v->pic;
            $result['p_props'] = "";
            $result['p_date'] = $v->date;
            $result['p_type'] = $v->type;
            array_push($results, $result);
        }
        $data = array('products' => $results);

        $data = array('products' => $results);
        echo json_encode(array("bizCode"=>1, "memo"=>"", "data"=>$data));
        exit;
    }else{
        echo json_encode(array("bizCode"=>0, "memo"=>"非ajax请求", "data"=>array()));
        exit;
    }
}
if($client_action === "isThere"){
    $where = "(user_id=$user_id)";
    $sql = "select id from `products` where ($where)";
    $data = $db->queryUniqueObject($sql);
    $db->close();

    if($data){
        $result = array();
        $result['p_id'] = $data->id;
        $result['user_id'] = $data->user_id;
        $result['p_name'] = $data->name;
        $result['p_count'] = $data->count;
        $result['p_price'] = $data->price;
        $result['p_from'] = $data->from;
        $result['p_man'] = $data->man;
        $result['p_pic'] = $data->id;
        $result['p_props'] = "";
        $result['p_date'] = $data->date;
        $result['p_type'] = $data->type;
        echo json_encode(array("bizCode"=>1, "memo"=>"", "data"=>$result));
        exit;
    }else{
        echo json_encode(array("bizCode"=>0, "memo"=>"仓库里没有商品", "data"=>array()));
        exit;
    }
}
?>