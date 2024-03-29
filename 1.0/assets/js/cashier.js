/**
 * Created with JetBrains PhpStorm.
 * User: praise
 * Date: 10/19/13
 * Time: 11:41 PM
 * To change this template use File | Settings | File Templates.
 */
define(function (require, exports, module) {
    var $ = require('zepto.min.js');
    var IO = require("io.js");
    var Utils = require("utils.js");
    var Rent = require("rent.js");

    var globallr = 0;
    var currentSelectedProduct = null;

    window.alert = function (msg) {
        navigator.notification.alert(
            msg,  // message
            function () {
            },         // callback
            '记账台',            // title
            '知道了'                  // buttonName
        );
    };

    $('#J-showPerfBtn').on("click", function () {
        showPerf();
    });
    $('#J-perf .close').on("click", function () {
        hidePerf();
    });
    $('#J-queryProductsBtn').on('click', function (e) {
        e.preventDefault();
        gotoQueryProducts();
    });
    $('#J-backToCashierBtn').on("click", function (e) {
        e.preventDefault();
        gotoCashier();
    });
    $('#J-addPriceBtn').on("click", function () {
        addPrice();
    });
    $('#J-soldCount').on("blur", function () {
        changePricesSelect();
    });
    $('#J-queryBtn').on("click", function () {
        if ($('#J-parentTypes').val() || $.trim($('#J-searchText').val())) {
            resetQueryProducts();
        }
    });
    $('#J-requestMoreBtn').on("click", function () {
        queryProducts($(this));
    });
    $('#J-cashierBtn').on("click", function (e) {
        e.preventDefault();
        e.stopPropagation();
        selling();
    });
    $('#J-zj a').unbind().bind("click", function () {
        Rent.add(function (data) {
            if (data.bizCode === 1 && data.data && data.data.rent) {
                var zj = (data.data.rent.price * 1).toFixed(2).split('.');
                $('#J-zj').html(zj[0] + "<small>." + zj[1] + "</small>元");
                var _lr = (globallr - data.data.rent.price * 1).toFixed(2).split('.');
                $('#J-lr').html(_lr[0] + "<small>." + _lr[1] + "</small>元");
                Utils.tip(data.memo);
            }
        });
    });

    function gotoCashier() {
        $('#J-cashierContainer').show();
        $('#J-queryProductsContainer').hide();
    }

    function gotoQueryProducts() {
        window.scrollTo(0, 0);
        $('#J-cashierContainer').hide();
        $('#J-queryProductsContainer').show();
    }

    function addPrice() {
        var count = getCount();
        if (!count) {
            return;
        } else if (count === 1) {
            return alert('当前销售数量为'+count+'个，不能增加其它价格！');
        }
        var selects = $('#J-prices select');
        var selectedCount = 0;
        var options = '';
        $.each(selects, function (i, select) {
            var val = $(select).val();
            if ($.trim(val) && /^\d+$/.test(val * 1)) {
                selectedCount += val * 1;
            }
        });
        for (var i = 1; i < count + 1; i++) {
            var selected = '';
            if (i === (count - selectedCount)) {
                selected = "selected";
            }
            options += '<option value="' + i + '" ' + selected + '>' + i + '个</option>';
        }
        var html = '<li class="flexBox">' +
            '<input type="number" autocapitalize="off" class="box" placeholder="价格" />' +
            '<span>&Chi;</span>' +
            '<select class="box">' +
            options +
            '</select>' +
            '<span class="box J-del">删除</span>' +
            '</li>';
        $('#J-prices').append(html);
        $('#J-prices .J-del').unbind().bind("click", function (e) {
            $(this).parent().remove();
        });
    }

    function showPerf() {
        $('#J-perf').show();
        $('#J-showPerfBtn').hide();
    }

    function hidePerf() {
        $('#J-showPerfBtn').show();
        $('#J-perf').hide();
    }

    function changePricesSelect() {
        var count = getCount();
        if (count) {
            var selects = $('#J-prices select');
            var options = '';
            for (var i = 1; i < count + 1; i++) {
                var selected = '';
                if (i === count) {
                    selected = "selected";
                }
                options += '<option value="' + i + '" ' + selected + '>' + i + '个</option>';
            }
            $.each(selects, function (i, select) {
                $(select).html(options);
            });
        }
    }

    function getCount() {
        var count = $.trim($('#J-soldCount').val());
        if (!count) {
            alert("请填写销售数量");
            return false;
        } else if (count * 1 === 0) {
            alert("销售数量不能为0");
            return false;
        } else if (!/^\d+$/.test(count)) {
            alert("销售数量必须是整数");
            return false;
        }
        count = count * 1;
        return count;
    }

    var pageNum = 1;
    var tipBox = $('#J-tip');
    var productList = $('#J-productList');
    var queryProductsIO = null;

    function queryProducts(byMoreBtn) {
        var parentType = $.trim($('#J-parentTypes').val());
        var searchText = $.trim($('#J-searchText').val());
        var data = '';
        if (parentType) {
            data += "type=" + parentType;
        }
        if (searchText) {
            if (data) {
                data += '&name=' + encodeURI(searchText);
            } else {
                data += 'name=' + encodeURI(searchText);
            }
        }

        $('#J-requestMoreBtn').html("更多商品");

        if (data) {
            if(queryProductsIO){
                queryProductsIO.ajaxObj.abort();
            }
            queryProductsIO = new IO({
                url: "products.php",
                data: data + "&pageNum=" + pageNum + "&action=query",
                timeoutcallback: function (){
                    alert("查询商品超时，请重试！");
                    if (byMoreBtn) {
                        byMoreBtn.bind("click", function (e){
                            e.preventDefault();
                            queryProducts(byMoreBtn);
                        });
                        byMoreBtn.html("发生异常，点击重试");
                    } else {
                        tipBox.html("查询商品超时，请重试").show().addClass("t-f50");
                        productList.hide();
                    }
                },
                on: {
                    start: function () {
                        if (byMoreBtn) {
                            byMoreBtn.unbind();
                            byMoreBtn.html("正在查询...");
                        } else {
                            tipBox.html("正在查询...").show().removeClass("t-f50");
                            productList.hide();
                        }
                    },
                    success: function (result) {
                        if (byMoreBtn) {
                            byMoreBtn.html("更多商品");
                        }
                        if (result.bizCode === 1) {
                            if (result.data.products.length >= 1) {
                                pageNum++;
                                renderProducts(result.data.products);
                            } else {
                                if (byMoreBtn) {
                                    byMoreBtn.html("没有了");
                                } else {
                                    noData(data);
                                }
                            }
                        }else{
                            tipBox.html(result.memo).show().addClass("t-f50");
                        }
                        if(byMoreBtn){
                            byMoreBtn.bind("click", function (e){
                                e.preventDefault();
                                queryProducts(byMoreBtn);
                            });
                        }
                    },
                    error: function (data) {
                        if (byMoreBtn) {
                            byMoreBtn.bind("click", function (e){
                                e.preventDefault();
                                queryProducts(byMoreBtn);
                            });
                            byMoreBtn.html("发生异常，点击重试");
                        } else {
                            tipBox.html("查询商品发生异常").show().addClass("t-f50");
                            productList.hide();
                        }
                    }
                }
            });
        queryProductsIO.send();
        }
    }

    function noData(data) {
        tipBox.html('没有您要的商品，现在就<a href="add.html" target="_blank" title="添加商品">添加</a>').show();
        productList.hide();
    }

    function renderProducts(data) {
        tipBox.hide();
        var data2html = require("template.js");
        var html = '';
        var tem = '<li class="flexBox touchStatusBtn" data-id="{p_id}">' +
            '<input type="hidden" value="{p_props}">' +
            '<div class="imgSkin box">' +
            '<img src="{p_pic}" alt=""/>' +
            '</div>' +
            '<div class="information box">' +
            '<p class="name">{p_name}</p>' +
            '<p class="pprice">单价：{int}<small>.{float}</small>元</p>' +
            '<p class="count">库存：<span class="J-count">{p_count}</span>个</p>' +
            '</div>' +
            '</li>';
        $.each(data, function (i, d) {
            if(d.p_pic.indexOf("attachments") !== -1){
                d.p_pic = "http://115.29.39.106/"+d.p_pic;
            }

            if(d.p_pic.indexOf("base64") < 0 && d.p_pic.indexOf("attachments") < 0){
                var user = JSON.parse(localStorage.getItem("user"));
                d.p_pic = "http://115.29.39.106/attachments/"+user.attachmentsDir + "/thumb_"+d.p_pic;
            }
            var price = (d.p_price * 1).toFixed(2);
            price = price.split(".");
            d.p_price = price;
            d.int = price[0];
            d.float = price[1];
            html += data2html(tem, d);
        });
        productList.show().find("ul").append(html);
        bindUItoPlist();
    }

    function bindUItoPlist() {
        var preview = $('#J-cashierProductPreview');
        productList.find("li").unbind().bind("click", function () {
            currentSelectedProduct = $(this);
            var props = $.trim($(this).find("input[type=hidden]").val());
            preview.html($(this).html()).attr("data-id", $(this).attr("data-id"));
            $('#J-cashierTable').find(".product-property").remove();
            if (props) {
                insertProps(Utils.props2Array(props));
            }
            gotoCashier();
            window.scrollTo(0, preview.offset().top);
        });
    }

    function resetQueryProducts() {
        pageNum = 1;
        productList.find("ul").html("");
        queryProducts();
    }

    function insertProps(data) {
        var html = '';
        for (name in data) {
            var content = '';
            $.each(data[name], function (i, v) {
                content += '<label>' +
                    '<input type="radio" name="' + name + '" value="' + v + '"/>' +
                    v +
                    '</label>';
            });
            html += '<tr class="product-property">' +
                '<td><span class="J-propName">' + name + '</span>：</td>' +
                '<td>' +
                content +
                '</td>' +
                '</tr>';
        }
        $('#J-cashierTable').append(html);
        var props = $('#J-cashierTable .product-property');
        $.each(props, function (j, prop) {
            $(prop).find('label').unbind().bind("click", function () {
                $(prop).find('label').removeClass("selected").find("input[type=radio]").attr("checked", "");
                $(this).addClass("selected").find("input[type=radio]").attr("checked", true);
            });
        });
    }

    var tradeData = {};

    function selling() {
        tradeData = {};
        if (validate()) {
            navigator.notification.confirm("确认记账？", function (which) {
                if (which === 1) {
                    new IO({
                        url: "cashier.php",
                        data: "action=selling&count=" + tradeData.count + "&detail=" + tradeData.detail + "&props=" + tradeData.props + "&id=" + tradeData.id,
                        on: {
                            start: function () {
                                Utils.loading.show("正在记账...");
                            },
                            success: function (data) {
                                if (data.bizCode === 1) {
                                    Utils.loading.warn("记账成功");
                                    updateCount(data.data);
                                    getTodayPerf();
                                    setTimeout(function () {
                                        Utils.loading.hide();
                                    }, 1500);
                                    resetCashier();
                                } else {
                                    Utils.loading.error(data.memo);
                                    setTimeout(function () {
                                        Utils.loading.hide();
                                    }, 1500);
                                }
                            },
                            error: function () {
                                Utils.loading.error("记账发生异常，请重试");
                                setTimeout(function () {
                                    Utils.loading.hide();
                                }, 1500);
                            }
                        },
                        timeoutcallback: function (){
                            alert("记账超时，请重试！");
                            Utils.loading.hide();
                        },
                    }).send();
                }
            }, "记账台", "确认,取消");
        }
    }

    function validate() {
        var result = true;
        var props = $('#J-cashierTable .product-property');
        var pricesNode = $('#J-prices li');
        var detail = [];

        if (!$('#J-cashierProductPreview').attr("data-id")) {
            result = false;
            navigator.notification.alert(
                "请先选择一个商品",  // message
                function () {
                    gotoQueryProducts();
                },         // callback
                '记账台',            // title
                '知道了'                  // buttonName
            );
        } else if (!getCount()) {
            result = false;
        } else if (getCount() > ($.trim($('#J-cashierProductPreview .J-count').html()) * 1)) {
            Utils.tip("销售数量大于当前商品的库存！", 3000);
        }
        if (result && pricesNode.length >= 1) {
            var totalCount = 0;
            $.each(pricesNode, function (i, pn) {
                var price = $.trim($(pn).find("input[type=number]").val()) * 1;
                var count = $.trim($(pn).find("select").val()) * 1;
                totalCount += count;
                if (!price) {
                    alert("第 " + (i + 1) + " 个销售价格不能为空");
                    result = false;
                    return false;
                } else if (!/^\d+\.?\d{0,2}$/.test(price)) {
                    result = false;
                    alert("第 " + (i + 1) + " 个销售价格不正确");
                    return false;
                } else if (!count) {
                    alert("第 " + (i + 1) + " 个销售数量不能为空");
                    result = false;
                    return false;
                } else if ((count <= 0) || (!/^\d+$/.test(count))) {
                    result = false;
                    alert("第 " + (i + 1) + " 个销售数量不正确");
                    return false;
                }
            });

            if (result && (totalCount !== getCount())) {
                result = false;
                if (pricesNode.length > 1) {
                    alert("" + pricesNode.length + "个销售价格的销售数量和与总销售数量不相等");
                } else {
                    alert("销售价格对应的销售数量与总销售数量不匹配");
                }
            }
            $.each(pricesNode, function (i, pn) {
                var price = $.trim($(pn).find("input[type=number]").val()) * 1;
                var count = $.trim($(pn).find("select").val()) * 1;
                detail.push('' + price + '*' + count + '');
            });
        }
        if (result && props.length >= 1) {
            $.each(props, function (j, prop) {
                var name = $.trim($(prop).find(".J-propName").html());
                var radios = $(prop).find("input[type=radio]");
                var checked = 0;
                $.each(radios, function (k, radio) {
                    if ($(radio).attr("checked")) {
                        checked++;
                        return false;
                    }
                });
                if (checked <= 0) {
                    alert("请选择 " + name + " 的属性");
                    result = false;
                    return false;
                }
            });
        }

        if (result) {
            tradeData.count = getCount();
            tradeData.id = $('#J-cashierProductPreview').attr("data-id");
        }

        tradeData.detail = detail.join("|");
        if (result && props.length >= 1) {
            var propsData = [];
            $.each(props, function (j, prop) {
                var name = $.trim($(prop).find(".J-propName").html());
                var radios = $(prop).find("input[type=radio]");
                $.each(radios, function (k, radio) {
                    if ($(radio).attr("checked")) {
                        propsData.push('' + name + ':' + $.trim($(radio).val()) + '');
                    }
                });
            });
            tradeData.props = propsData.join('|');
        }

        if (props.length <= 0) {
            tradeData.props = '';
        }

        return result;
    }

    function updateCount(count) {
        $('#J-cashierProductPreview').find('.J-count').html(count);
        currentSelectedProduct.find('.J-count').html(count);
    }

    function getTodayPerf() {
        var perf = require("performance.js");
        var today = new Date();
        var y = today.getFullYear();
        var m = Utils.to2Num(today.getMonth() + 1);
        var d = Utils.to2Num(today.getDate());
        var date = y + "-" + m + "-" + d;
        var href = $('.gotoDetail').attr('href');
        if (href.indexOf("?") !== -1) {
            href += '&date=' + date;
        } else {
            href += '?date=' + date;
        }
        $('.gotoDetail').attr('href', href);
        perf.io({
            range: true,
            data: "action=query&start=" + date + '&end=' + date,
            on: {
                success: function (data) {
                    if (data.bizCode !== 1) {
                        return;
                    } else if (data.data.products && data.data.products.length === 0) {
                        data.yye = 0;
                        data.cb = 0;
                        data.lr = 0;
                    }
                    $('#J-tradeCount').html(data.data.products.length);
                    Rent.getRange(date, date, function (rentData) {
                        if (data.data.products && data.data.products.length === 0) {
                            data.zj = 0;
                        }
                        if (rentData.data && rentData.data.rents && rentData.data.rents.length >= 1) {
                            $.each(rentData.data.rents, function (i, rent) {
                                data.zj += rent.price * 1;
                            });
                        }

                        data.lr = data.yye - data.cb - data.zj;
                        updatePerf(data);
                    });
                }
            }
        });
    }

    function updatePerf(data) {
        globallr = data.lr;
        var zj = data.zj.toFixed(2).split('.');
        var lr = data.lr.toFixed(2).split('.');
        var cb = data.cb.toFixed(2).split('.');
        var yye = data.yye.toFixed(2).split('.');
        $('#J-yye').html(yye[0] + "<small>." + yye[1] + "</small>元");
        $('#J-cb').html(cb[0] + "<small>." + cb[1] + "</small>元");
        $('#J-lr').html(lr[0] + "<small>." + lr[1] + "</small>元");
        (zj[0] > 0 || zj[1] > 0) && ($('#J-zj').html(zj[0] + "<small>." + zj[1] + "</small>元"));
    }

    function resetCashier() {
        $('#J-prices').html('<li class="flexBox">'+
            '<input type="number" autocapitalize="off" class="box" placeholder="销售价格"/>'+
            '<span>&Chi;</span>'+
            '<select class="box">'+
            '<option value="1">1个</option>'+
            '</select>'+
            '</li>');
        $('#J-soldCount').val('1');
        $('#J-prices input[type=number]').val('');
        $('#J-prices select').html('<option value="1">1个</option>');
        $('#J-cashierProductPreview').empty().attr('data-id', '');
    }

    function getTypes() {
        var type = require("types.js");
        type.query(function (data) {
            if (data.bizCode === 1 && data.data && data.data.types.length >= 1) {
                var options = '<option value="">商品分类</option>';
                $.each(data.data.types, function (i, type) {
                    options += '<option value="' + type.id + '">' + type.name + '</option>';
                });
                $('#J-parentTypes').html(options);
                $('#J-parentTypes').unbind().bind("change", function () {
                    if ($('#J-parentTypes').val()) {
                        resetQueryProducts();
                    }
                });
            }else{
                $('#J-parentTypes').html('<option value="">无商品分类</option>');
            }
        });
    }

    getTypes();
    getTodayPerf();
});