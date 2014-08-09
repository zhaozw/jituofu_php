<?php
/**
 * Created by JetBrains PhpStorm.
 * User: praise
 * Date: 9/26/13
 * Time: 9:46 PM
 * To change this template use File | Settings | File Templates.
 */
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

if($client_action === "selling"){
    $id = @$_POST['id'];
    $count = @$_POST['count'];
    $detail = @$_POST['detail'];
    $man = @$_POST['man'];
    $props = @$_POST['props'];
    $date = date("Y-m-d H:i:s");

    if(!$man){
        $man = '';
    }

    if(!$id){
        echo son_encode(array("bizCode" => 0, 'memo' => "缺少商品id", "data" => array()));
        exit;
    }else if(!$detail){
        echo json_encode(array("bizCode" => 0, 'memo' => "缺少销售价格", "data" => array()));
        exit;
    }

    $query_kc_sql = "select count,price from `products` where (`user_id`=$user_id and `id` = '$id' and `status`=1)";
    $kc_data = $db->queryObject($query_kc_sql);
    if(!$kc_data){
        echo json_encode(array("bizCode"=>0, "memo" => "该商品不存", "data" => array()));
        exit;
    }
//else if($kc_data->p_count <= 0){
//    echo json_encode(array("bizCode"=>0, "memo" => "该商品的库存为 0"));
//    exit;
//}

    $price = $kc_data->price;//成本价
    $selling_price = 0;//销售单价
    $details_split = preg_split("/\|/", $detail);//将销售详情格式化数组
    $prices = array();//销售详情中的所有销售单价
    foreach($details_split as $k => $v){
        $detail_split = preg_split("/\*/", $v);
        array_push($prices, $detail_split[0]);
    }
    $selling_price = min($prices);//使用最小的销售单价

    $sql = "insert into cashier(`user_id`, `pid`, `selling_count`,`selling_price`, `who`, `date`, `price`) values($user_id, '$id', $count, $selling_price , '$man', '$date', '$price')";
    $insert_to_cashier_result = $db->query($sql);


    $new_count = $kc_data-> count - $count;
//if($new_count <= 0){
//    $new_count = 0;
//}

    if($insert_to_cashier_result){
        $update_kc_sql = "update `products` set `count` = $new_count where (`user_id`=$user_id and `id` = '$id' and `status`=1)";
        $updated_kc_result = $db->query($update_kc_sql);
    }else{
        echo json_encode(array("bizCode"=>0, "memo" => "记账失败，请重试", "data" => array()));
        exit;
    }
    $db->close();
    if($updated_kc_result){
        echo json_encode(array("bizCode"=>1, "memo" => "记账成功", "data" => $new_count));
        exit;
    }else{
        echo json_encode(array("bizCode"=>1, "memo" => "记账成功，但是更新库存失败", "data" => $new_count));
        exit;
    }
}
?>