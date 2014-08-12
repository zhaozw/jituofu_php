<?php

/**
 * 2.0发布时，做数据迁移
 */
include_once('../config/config.php');
include_once('../' . $libs_dir . '/db.php');
require_once("../models/config.php");

$db = new DB($db_name, $db_host, $db_username, $db_password);
$db->query("SET NAMES 'UTF8'");

$type = @$_GET['type'];

//转换cashier表中detail数据
if($type == 'detail'){
    $sql = "select detail, id from cashier where ((detail like '%*%'))";
    $records = $db->queryManyObject($sql);

    $total = 0;
    $cashier_id_sellingPrice = array();
    $update_counter = 0;
    foreach ($records as $k => $record) {
        $detail_split = @preg_split("/\|/", $record->detail); //将销售详情格式化数组
        $prices = array(); //销售详情中的所有销售单价

        if ($detail_split && count($detail_split) > 0) {
            $total++;
            foreach ($detail_split as $k => $price) {
                $price_split = preg_split("/\*/", $price);
                if ($price_split[0] && strlen($price_split[0]) > 0) {
                    array_push($prices, $price_split[0]);
                }
            }
            if (count($prices) > 0) {
                $selling_price = min($prices); //使用最小的销售单价
                array_push($cashier_id_sellingPrice, array("id" => $record->id, "selling_price" => $selling_price));
            }
        }
    }


    foreach ($cashier_id_sellingPrice as $k => $cis) {
        if ($update_counter >= 200) {
            break;
        }
        $id = $cis['id'];
        $selling_price = $cis['selling_price'];
        $sql = "update `cashier` set `selling_price` = $selling_price where `id` = '$id'";

        if ($selling_price && strlen($selling_price) > 0 && $db->query($sql)) {
            $sql = "update `cashier` set `detail` = '' where `id` = '$id'";
            $db->query($sql);
            $update_counter++;
        }
    }
    echo json_encode(array("total"=>$total, "update_counter" => $update_counter));
    exit;
}else if($type == "ds"){
    $sql = "select display_name, store_settings, id from rib_users where  length(display_name)>0";
    $records = $db->queryManyObject($sql);

    $total = 0;
    $update_counter = 0;
    $rib_users = array();
    foreach ($records as $k => $record) {
        $display_name = $record->display_name;
        $store_settings = $record->store_settings;
        $total++;

        $data = array("id" => $record->id);
        if ($display_name && strlen($display_name) > 0) {
            $data['display_name'] = $display_name;
        }

        if ($store_settings && strlen($store_settings) > 0) {
            $store_settings_json = @json_decode($store_settings);
            if($store_settings_json){
                $tip_rent = 0;
                if($store_settings_json->tip_rent == "on"){
                    $tip_rent = 1;
                }
                $data['tip_rent'] = $tip_rent;
            }
        }else{
            $data['tip_rent'] = 0;
        }
        array_push($rib_users, $data);
    }


    foreach ($rib_users as $k => $ru) {
        if ($update_counter >= 200) {
            break;
        }
        $id = $ru['id'];
        $tip_rent = @$ru['tip_rent'];
        $name = @$ru['display_name'];

        if(!$name){
            continue;
        }

        $exist_sql = "select * from`store_settings` where `user_id` = '$id'";
        $insert_sql = "insert into `store_settings`(user_id,tip_rent,name) values ($id,$tip_rent,'$name')";
        $update_sql = "update `store_settings` set `name` = '$name',`tip_rent`=$tip_rent where `user_id` = '$id'";

        $exist = $db->queryObject($exist_sql);
        if($exist){
            if($db->query($update_sql)){
                $update_counter++;
            }
        }else{
            if($db->query($insert_sql)){
                $sql = "update `rib_users` set `display_name` = '', `store_settings`='' where `id` = '$id'";
                $db->query($sql);

                $update_counter++;
            }
        }
    }
    echo json_encode(array("total"=>$total, "update_counter" => $update_counter));
    exit;
}else if($type === 'cost'){
    $sql = "select pid,id from cashier where (price=0)";
    $records = $db->queryManyObject($sql);

    $total = 0;
    $cashier_pids = array();
    $update_counter = 0;
    foreach ($records as $k => $record) {
        $total++;
        array_push($cashier_pids, array("pid" => $record->pid, "id" => $record->id));
    }


    foreach ($cashier_pids as $k => $v) {
        if ($update_counter >= 200) {
            break;
        }
        $pid = $v['pid'];
        $cashier_id = $v['id'];
        $sql = "select price from products where (id=$pid)";
        $record = $db->queryObject($sql);
        $price = $record ? $record->price : 0;

        $sql = "update `cashier` set `price` = $price where `id` = $cashier_id";
        if ($db->query($sql)) {
            $update_counter++;
        }
    }
    echo json_encode(array("total"=>$total, "update_counter" => $update_counter));
    exit;
}
?>