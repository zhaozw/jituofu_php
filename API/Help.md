帮助模块
========
* [获取一串帮助数据](#获取一串帮助数据)
* [这条内容对我有帮助](#这条内容对我有帮助)
* [这条内容对我无帮助](#这条内容对我无帮助)

####获取一串帮助数据
接口:`help/index`或`help`
######入参
<table>
    <tr>
        <td>参数名称</td>
        <td>数据类型</td>
        <td>描述</td>
        <td>取值</td>
        <td>参数值是否可空</td>
        <td>其它</td>
    </tr>
    <tr>
        <td>pageNum</td>
        <td>int</td>
        <td>页码</td>
        <td></td>
        <td>NO</td>
        <td></td>
    </tr>
    <tr>
        <td>limit</td>
        <td>int</td>
        <td>每页输出的数据量</td>
        <td></td>
        <td>NO</td>
        <td></td>
    </tr>
    <tr>
        <td>sort</td>
        <td>int</td>
        <td>排序指令</td>
        <td>
          1按照录入时间倒序<br />
          2按照录入时间升序<br />
        </td>
        <td>NO</td>
        <td></td>
    </tr>
</table>
######出参
<table>
    <tr>
        <td>参数名称</td>
        <td>数据类型</td>
        <td>描述</td>
        <td>取值</td>
        <td>参数值是否可空</td>
        <td>其它</td>
    </tr>
    <tr>
        <td>helps</td>
        <td>Array</td>
        <td>帮助内容的集合</td>
        <td></td>
        <td>NO</td>
        <td></td>
    </tr>
</table>

####这条内容对我有帮助
接口:`help/yes`
######入参
<table>
    <tr>
        <td>参数名称</td>
        <td>数据类型</td>
        <td>描述</td>
        <td>取值</td>
        <td>参数值是否可空</td>
        <td>其它</td>
    </tr>
    <tr>
        <td>id</td>
        <td>int</td>
        <td>帮助内容的id</td>
        <td></td>
        <td>NO</td>
        <td></td>
    </tr>
</table>
######出参
见[公共参数](Public.md)

####这条内容对我无帮助
接口:`help/no`
######入参
<table>
    <tr>
        <td>参数名称</td>
        <td>数据类型</td>
        <td>描述</td>
        <td>取值</td>
        <td>参数值是否可空</td>
        <td>其它</td>
    </tr>
    <tr>
        <td>id</td>
        <td>int</td>
        <td>帮助内容的id</td>
        <td></td>
        <td>NO</td>
        <td></td>
    </tr>
</table>
######出参
见[公共参数](Public.md)
