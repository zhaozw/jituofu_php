/**
 * Created with JetBrains PhpStorm.
 * User: praise
 * Date: 10/11/13
 * Time: 8:41 PM
 * To change this template use File | Settings | File Templates.
 */
define(function (require, exports, module){
    var $ = require('zepto.min.js');
    var Widgets = require('widgets.js');
    var Operation = require("operation.js");
    var Util = require('utils.js');
    var Refund = require('refund.js');
    var Rent = require('rent.js');;

    var btn = $('#J-queryBtn');
    var startDate = $('#J-date-start');
    var endDate = $('#J-date-end');
    var loadingImg = $('.loadingImg');
    var dateName = $('.dateName');

    var yyeNode = $('.yye'),
        lrNode = $('.lr'),
        cbNode = $('.cb');

    function getStartTIme(){
        var val = $.trim(startDate.val());
        if(!val){
            return null;
        }
        return val.replace(/\//g, '-');
    }

    function getEndTIme(){
        var val = $.trim(endDate.val());
        if(!val){
            return null;
        }
        return val.replace(/\//g, '-');
    }

    startDate.bind('change', function (){
        if(getStartTIme() && !getEndTIme()){
            endDate.val(getStartTIme());
        }
    });

    btn.bind('click', function (e){
        e.preventDefault();
        if(!getStartTIme()){
            return alert('请选择开始时间');
        }else if(!getEndTIme()){
            return alert('请选择结束时间');
        }
        queryOperation();
    });

    $('#J-show-selled-products').bind('click', function (e){
        e.preventDefault();
        if(!getStartTIme()){
            return alert('请选择开始时间');
        }else if(!getEndTIme()){
            return alert('请选择结束时间');
        }
        queryProducts();
    });

    function queryProducts(){
        var api = 'controler/queryProducts.php';
        $.ajax({
            url: api,
            type: 'post',
            data: 'action=custom&'+"start="+getStartTIme()+'&end='+getEndTIme(),
            success: function (data){
                if(data.bizCode*1 === 1){
                    if(data.data.products){
                        renderSelledProducts(data.data);
                    }
                }
            },
            dataType: "json"
        });
    }

    function renderSelledProducts(data){
        var html = '';
        for(dateType in data.products){
            var dateHtml = '<p class="dateCat">'+dateType+' 的售出商品</p>';
            var ulHtml = dateHtml+'<ul>';
            var dateProducts = data.products[dateType];
//            var pdata = data.products[d];
//            var p = data[d][0];
//            if(!p){
//                continue;
//            }


            $.each(dateProducts, function (i, prod){
                var p = data[prod.p_id][0];

                var p_price = p.p_price*1;
                var p_name = p.p_name;

                var detail = prod.detail.split('|');
                var counter = 0;
                var detailHtml = '';
                var date = prod.date;
                $.each(detail, function (j, _det){
                    if(_det){
                    _det = _det.split('*');
                    if(_det[1]){
                        counter += _det[1]*1;
                    }
                    detailHtml += '<p class="detail" data-detailNum="'+(j+1)+'" data-detail="'+(_det[0]*1)+'*'+(_det[1]*1)+'">'+(_det[0]*1).toFixed(2)+'元 x '+_det[1]*1+'</p>';
                    }
                });
                ulHtml += '<li data-id="'+prod.order_id+'">'+
                    '<div class="imgBox"><img data-src="'+ p.p_pic+'" / data-id="'+ p.p_id+'"></div>'+
                    '<div class="info">'+
                    '<p class="pName">'+p_name+'</p>'+
                    '<div class="extra">'+
                    '<p class="kcBox">销售量：<span>'+counter+'</span> 个</p>'+
                    '<div>详细：'+detailHtml+'</div>'+
                    '</div>'+
                    '<p>时间：'+(date.split(' ')[1])+'</p>'+
                    '</div>'+
                    '<div class="controler">' +
                    '<a class="refund" href="javascript:void(0)">退单</a>'+
                    '</div>'+
                    '</li>';
                //orderId.push(prod.order_id);
            });
            ulHtml += '</ul>';
            html+= ulHtml;
        }
        html += '';

        $('.selled-products-box').find('ul').remove();
        $('.selled-products-box').append(html);
        Util.queryBase64($('.selled-products-box').find('.imgBox img'));
        $('.selled-products-box').find('li').unbind().bind('click', function (){
            var id = $(this).attr('data-id');
            var detailNodes = $(this).find('.detail');
            var details = [];
            $.each(detailNodes, function (i, n){
                details.push($(n).attr('data-detail'));
            });

        });
        $('.selled-products-box .controler .refund').unbind().bind('click', function (){
            var id = $(this).parent().parent().attr('data-id');
            var detailNodes = $(this).parent().parent().find('.detail');
            var details = [];
            $.each(detailNodes, function (i, n){
                details.push($(n).attr('data-detail'));
            });

            var data = {
                name: $(this).parent().parent().find('.pName').html(),
                id: id,
                details: details
            };
            Refund.showPanel(data, function (data){
                if(data.data.deleted){
                    alert("销售记录已删除");
                    location.reload();
                }else if(data.data.sold){
                    alert("退货成功");
                    location.reload();
                }
            });
        });
    }

    function beforeQuery(){
        dateName.html(getStartTIme()+' 到 '+getEndTIme()+'  ');
        loadingImg.show();
    }

    function queryOperation(){
        beforeQuery();

        Operation.io({
            range: true,
            data: "start="+getStartTIme()+'&end='+getEndTIme(),
            on: {
                success: function (data){
                    loadingImg.hide();
                    renderOperation(data);
                },
                error: function (){

                }
            }
        });

        function renderOperation(data){
            yyeNode.html(data.yye.toFixed(2));
            cbNode.html(data.cb.toFixed(2));
            lrNode.html((data.yye-data.cb).toFixed(2));

            Rent.getRange(getStartTIme(), getEndTIme(), function (o){
                var prices = 0;
                $.each(o.data.dates, function (i, date){
                    prices += date.price*1;
                });
                $('.zj').html(prices.toFixed(2));
                $('.clr').html((data.yye-prices-data.cb).toFixed(2));
            });
        }
    }
});
