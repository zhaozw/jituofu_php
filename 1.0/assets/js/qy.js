/**
 * Created with JetBrains PhpStorm.
 * User: praise
 * Date: 10/19/13
 * Time: 11:41 PM
 * To change this template use File | Settings | File Templates.
 */
define(function (require, exports, module) {
    var $ = require('zepto.min.js');

    var api = "/controlers/qy.php";
    var type = null;

   $('#Jq-ds').bind("click", function (e){
       e.preventDefault();
       type = "ds";
       io();
   });

    $('#Jq-detail').bind("click", function (e){
        e.preventDefault();
        type = "detail";
        io();
    });

   function io(){
       $('#J-tip').html("处理中...");
       $('#J-result').html("");
       $.ajax({
           url: api,
           data: "type="+type,
           dataType: "json",
           success: function (data){
               $('#J-tip').html("");
               $('#J-result').html("处理结果：查询到 "+data.total+" 条记录，处理 "+data.update_counter+" 条");
               if(data.total > 0){
                   setTimeout(function (){
                       io();
                   }, 3000);
               }else{
                   $('#J-result').html("处理完毕");
               }
           }
       });
   }
});