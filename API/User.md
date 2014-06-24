User模块
========
* [注册](#注册)
* [登录](#登录)
* [获取用户信息](#获取用户信息)
* [登出](#退出)
* [更新邮箱](#更新邮箱)
* [登录密码修改](#登录密码修改)
* [忘记密码](#忘记密码)
* [忘记密码/重新设置密码](#重新设置密码)

####注册
接口:`users/create`
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
        <td>username</td>
        <td>String</td>
        <td></td>
        <td></td>
        <td>NO</td>
        <td></td>
    </tr>
    <tr>
        <td>password</td>
        <td>String</td>
        <td></td>
        <td></td>
        <td>NO</td>
        <td></td>
    </tr>
    <tr>
        <td>cpassword</td>
        <td>String</td>
        <td></td>
        <td></td>
        <td>NO</td>
        <td>二次输入的登录密码</td>
    </tr>
    <tr>
        <td>email</td>
        <td>String</td>
        <td></td>
        <td></td>
        <td>NO</td>
        <td></td>
    </tr>
    <tr>
        <td>clientId</td>
        <td>String</td>
        <td>客户端设备ID</td>
        <td></td>
        <td>NO</td>
        <td>服务器根据这个ID加uniqueId生成唯一的字符串返回给客户端</td>
    </tr>
    <tr>
        <td>location</td>
        <td>String</td>
        <td>客户端经纬坐标值</td>
        <td>4324049,129444</td>
        <td>Yes</td>
        <td></td>
    </tr>
    <tr>
        <td>mac</td>
        <td>String</td>
        <td>手机mac地址</td>
        <td></td>
        <td>Yes</td>
        <td></td>
    </tr>
    <tr>
        <td>imei</td>
        <td>String</td>
        <td>手机imei</td>
        <td></td>
        <td>Yes</td>
        <td></td>
    </tr>
    <tr>
        <td>imsi</td>
        <td>String</td>
        <td>手机卡</td>
        <td></td>
        <td>Yes</td>
        <td></td>
    </tr>
    <tr>
        <td>model</td>
        <td>String</td>
        <td>手机型号</td>
        <td></td>
        <td>Yes</td>
        <td></td>
    </tr>
    <tr>
        <td>os</td>
        <td>String</td>
        <td>操作系统</td>
        <td></td>
        <td>Yes</td>
        <td></td>
    </tr>
</table>
For example:
```javascript
{
    "operation": {
        "username": "朱 琦", 
        "password": 123456, 
        "cpassword": 123456, 
        "email": "339379186@qq.com",
        "clientId": "4324325hgdhfwqr",
        "location": "432432,436469939",
        "mac": "mac",
        "imei": "imei",
        "imsi": "imsi",
        "model": "model",
        "os": "android 4.0"
    }
};
```
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
        <td>id</td>
        <td>String</td>
        <td>用户id,仅注册成功并且自动登录也成功后返回</td>
        <td></td>
        <td>No</td>
        <td>仅注册成功并且自动登录也成功后返回.如果注册成功,但自动登录失败时,不返回id数据</td>
    </tr>
    <tr>
        <td>cookie</td>
        <td>String</td>
        <td>服务端返回的cookie</td>
        <td></td>
        <td>NO</td>
        <td>仅注册成功并且自动登录也成功后返回,如果注册成功,但自动登录失败时,不返回cookie数据</td>
    </tr>
    <tr>
        <td>错误消息</td>
        <td>Hashmap</td>
        <td>包含了注册失败的详细原因</td>
        <td></td>
        <td>Yes</td>
        <td>仅在注册失败时返回</td>
    </tr>
</table>
注册成功后,接着系统会自动登录并返回登录用户id和生成的cookie,for example:
```javascript
//失败
{
    "public": {
        "resultStatus": 400, 
        "memo": "注册失败", 
        "updateUrl": "", 
        "updateTitle": ""
    }, 
    "operation": {
        "user_name": [
            "朱琦 已存在，请更换其它用户名"
        ], 
        "email": [
            "339379186@qq.com 已存在，请更换其它邮箱"
        ]
    }
}
//成功
{
    "public": {
        "resultStatus": 100, 
        "memo": "注册并登录成功", 
        "updateUrl": "", 
        "updateTitle": ""
    }, 
    "operation": {
        "id": "18", 
        "cookie": "7637756004cf4f215f5ef038018c7fc074805b41c208390fd5b19c4baaa84ba16"
    }
}
```
####登录
接口:`users/login`
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
        <td>username</td>
        <td>String</td>
        <td></td>
        <td></td>
        <td>No</td>
        <td></td>
    </tr>
    <tr>
        <td>password</td>
        <td>String</td>
        <td></td>
        <td></td>
        <td>No</td>
        <td></td>
    </tr>
    <tr>
        <td>clientId</td>
        <td>String</td>
        <td>客户端设备ID</td>
        <td></td>
        <td>NO</td>
        <td>服务器根据这个ID加uniqueId生成唯一的字符串返回给客户端</td>
    </tr>
    <tr>
        <td>mac</td>
        <td>String</td>
        <td>手机mac地址</td>
        <td></td>
        <td>Yes</td>
        <td></td>
    </tr>
    <tr>
        <td>imei</td>
        <td>String</td>
        <td>手机imei</td>
        <td></td>
        <td>Yes</td>
        <td></td>
    </tr>
    <tr>
        <td>imsi</td>
        <td>String</td>
        <td>手机卡</td>
        <td></td>
        <td>Yes</td>
        <td></td>
    </tr>
    <tr>
        <td>model</td>
        <td>String</td>
        <td>手机型号</td>
        <td></td>
        <td>Yes</td>
        <td></td>
    </tr>
    <tr>
        <td>os</td>
        <td>String</td>
        <td>操作系统</td>
        <td></td>
        <td>Yes</td>
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
        <td>id</td>
        <td>String</td>
        <td>用户id,仅登录成功返回</td>
        <td></td>
        <td>No</td>
        <td></td>
    </tr>
    <tr>
        <td>cookie</td>
        <td>String</td>
        <td>服务端返回的cookie</td>
        <td></td>
        <td>NO</td>
        <td>仅登录成功后返回</td>
    </tr>
    <tr>
        <td>错误消息</td>
        <td>Hashmap</td>
        <td>包含了登录失败的详细原因</td>
        <td></td>
        <td>Yes</td>
        <td>仅在登录失败时返回</td>
    </tr>
</table>
登录成功后,客户端把返回的id和cookie做加密后存储至本地
####获取用户信息
接口:`users/getInfo`

**获取用户信息的接口会验证userId,clientId与cookie是否匹配**

######入参
public中的userId和cookie必须要传入

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
        <td>clientId</td>
        <td>String</td>
        <td>客户端设备ID</td>
        <td></td>
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
        <td>id</td>
        <td>String</td>
        <td></td>
        <td></td>
        <td>NO</td>
        <td></td>
    </tr>
    <tr>
        <td>username</td>
        <td>String</td>
        <td></td>
        <td></td>
        <td>NO</td>
        <td></td>
    </tr>
    <tr>
        <td>email</td>
        <td>String</td>
        <td></td>
        <td></td>
        <td>NO</td>
        <td></td>
    </tr>
    <tr>
        <td>from</td>
        <td>String</td>
        <td>来自哪个社交平台</td>
        <td>91助手/百度助手</td>
        <td>NO</td>
        <td>默认是小店记账宝</td>
    </tr>
    <tr>
        <td>错误消息</td>
        <td>String</td>
        <td></td>
        <td></td>
        <td>Yes</td>
        <td></td>
    </tr>
</table>
For example:
```javascript
//成功
{
    "public": {
        "updateUrl": "", 
        "updateTitle": "", 
        "resultStatus": 100, 
        "memo": ""
    }, 
    "operation": {
        "id": "28", 
        "username": "朱琦", 
        "email": "339379186@qq.com", 
        "from": "小店记账宝"
    }
}
//失败
{
    "public": {
        "updateUrl": "", 
        "updateTitle": "", 
        "resultStatus": 400, 
        "memo": "缺少network参数"
    }, 
    "operation": [ ]
}

```
####退出
接口:`users/logout`

**安全退出接口会验证userId和uuid以及cookie是否匹配**

######入参
public中的userId和cookie必须要传入
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
        <td>clientId</td>
        <td>String</td>
        <td></td>
        <td></td>
        <td>NO</td>
        <td>服务端要清除当前用户下,当前设备下的指定的cookie</td>
    </tr>
</table>
服务端退出处理:
```php
public static function removeCookie($userId, $uuid, $cookie){
        //退出指定的userId, uuid(设备id),cookie
        $record = Device::model()->findByAttributes(array('user_id' => $userId, 'uuid' => $uuid, 'cookie' => $cookie));

        if ($record) {
            if($record->delete()){
                return true;
            }else{
                //删除记录失败时,客户端强制退出
                return false;
            }
        }else{
            //如果device表中没有查询到登录状态,返回false,客户端强制退出
            return false;
        }
    }
```
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
        <td>
        参见公共参数
        </td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
</table>
####更新邮箱
接口:`users/update`

更新失败时,operation字段里会返回准确的失败消息
######入参
public中的userId和cookie必须要传入
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
        <td>email</td>
        <td>String</td>
        <td></td>
        <td></td>
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
        <td>
        参见公共参数
        </td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
</table>
```javascript
//失败
{
    "public": {
        "updateUrl": "", 
        "updateTitle": "", 
        "resultStatus": 400, 
        "memo": "用户信息更新失败"
    }, 
    "operation": {
        "email": [
            "praise.cross@gmail.com 已存在，请更换其它邮箱"
        ]
    }
}
//成功
{
    "public": {
        "updateUrl": "", 
        "updateTitle": "", 
        "resultStatus": 100, 
        "memo": "邮箱已更新"
    }, 
    "operation": [ ]
}
```
####登录密码修改
接口:`users/update`

修改失败时,operation字段里会返回准确的失败消息
######入参
public中的userId和cookie必须要传入
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
        <td>opassword</td>
        <td>String</td>
        <td>当前登录密码</td>
        <td></td>
        <td>NO</td>
        <td></td>
    </tr>
    <tr>
        <td>password</td>
        <td>String</td>
        <td>新的登录密码</td>
        <td></td>
        <td>NO</td>
        <td></td>
    </tr>
    <tr>
        <td>cpassword</td>
        <td>String</td>
        <td>二次输入的新登录密码</td>
        <td></td>
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
        <td>
        参见公共参数
        </td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
</table>
密码修改成功后,需要重新登录.并且系统会清除当前用户在所有设备上的登录信息,服务端代码如下:
```php
/**
 * 删除指定用户下的所有设备上的登录信息
 * @param $userId
 * @return bool
 */
    public static function removeAllCookie($userId)
    {
        $records = Device::model()->findAllByAttributes(array('user_id' => $userId));

        if (!empty($records)) {
            foreach($records as $k => $record){
                $record->delete();
            }
            return true;
        }else{
            return false;
        }
    }
```
####忘记密码
接口:`users/forgot`
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
        <td>username</td>
        <td>String</td>
        <td></td>
        <td></td>
        <td>NO</td>
        <td></td>
    </tr>
    <tr>
        <td>email</td>
        <td>String</td>
        <td></td>
        <td></td>
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
        <td>
        id
        </td>
        <td>String</td>
        <td>用户id</td>
        <td></td>
        <td>Yes</td>
        <td>校验码通过后,才会返回用户id</td>
    </tr>
</table>
找回密码时,系统将下发6位随机字符串到用户的邮箱和手机,校验码通过后,系统返回用户id,凭这6位随机字串和用户id设定新密码

####重新设置密码
接口:`users/forgot`

public中的userId必须传入
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
        <td>password</td>
        <td>String</td>
        <td>新密码</td>
        <td></td>
        <td>NO</td>
        <td></td>
    </tr>
    <tr>
        <td>cpassword</td>
        <td>String</td>
        <td>二次输入的新密码</td>
        <td></td>
        <td>NO</td>
        <td></td>
    </tr>
    <tr>
        <td>token</td>
        <td>String</td>
        <td>系统下发的6位字符串</td>
        <td></td>
        <td>NO</td>
        <td></td>
    </tr>
    <tr>
        <td>step</td>
        <td>Int</td>
        <td>找回密码第几步</td>
        <td>2表示第二步,目前只有2步</td>
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
        <td>参见公共参数</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
</table>
