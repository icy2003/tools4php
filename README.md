# 我的工具集合

是时候整理一下自己写过的代码了

yii（2）上的工具倒是写了很多，所以……yii（2）之外呢？

其实很想把写过的一些yii（2）的挂件、组件放出来，但是，对于很多人来说，直接就可以用的可能会好些

所以才有了这个

**推荐PHP5.4+**

## Tree

树操作

`orderTreeUpDown` 对结构为`id-pid`的树从上到下排序，得到的结果用于重新创建新的一棵树，只保证了父节点在前面，不是一层一层往下

```
$array = [
    ['id' => 1, 'pid' => 0, 'name' => '1'],
    ['id' => 59, 'pid' => 58, 'name' => '8'],
    ['id' => 37, 'pid' => 1, 'name' => '2'],
    ['id' => 54, 'pid' => 1, 'name' => '3'],
    ['id' => 60, 'pid' => 59, 'name' => '10'],
    ['id' => 35, 'pid' => 20, 'name' => '9'],
    ['id' => 58, 'pid' => 1, 'name' => '4'],
    ['id' => 11, 'pid' => 1, 'name' => '5'],
    ['id' => 20, 'pid' => 1, 'name' => '6'],
    ['id' => 57, 'pid' => 54, 'name' => '7'],
];
print_r(Tree::orderTreeUpDown($array));
```

## Scroll

滚屏工具

`flushMessage` 滚屏输出一批文字，就跟批处理命令脚本一样>_<

```
$scroll = new Scroll();
$scroll->flushMessage(range(0, 1000));
```

## Validator

`rules` 强大的验证类的入口函数，用以过滤表单字段，验证规则参照doc里类对应文档的栗子，具体的用法参见每一个验证方法对应的常量

特点：

1. 默认使用yii2去实现唯一性判断，可以通过参数设置成yii
2. 规则格式参考yii2的核心验证器，轻松上手-。-
3. 开启debug模式，将会有完整的错误信息提示（如果你有很多字段验证失败的话），因此生产环境应该关闭debug模式
4. 开启debug模式，将会显示所有字段的使用说明，说明可以自定义，这些提示可以在开发阶段作为接口调试时候的提示
5. 可自定义的错误提示
6. 丰富的验证器和过滤器，其中filter过滤器和rule验证器几乎支持你想要的过滤和验证，在验证类内部也是用filter过滤器作为通用的处理函数
7. 以后可能会彻底从yii脱离出来（我自己写一些数据库相关的类），当然也可以继承重写`findOne`函数适应自己的项目
8. 未来更多的特性~~~

```
$rules = [
    [['mobile' => '手机号', 'phone' => '移动电话'], Validator::VALIDATOR_MOBILE, '手机号格式不正确'],
    [['createtime' => '时间', 'updatetime'], Validator::FILTER_FILTER, 'method' => 'strtotime',
        'value' => 'now', 'isEmpty' => 'empty'],
];
$formData = [
    'mobile' => '18888888888',
    'phone' => '1322222222',
    'createtime' => '2016-11-05',
    'updatetime' => 0
];
define('DEBUG', true);
$result = Validator::rules($formData, $rules);
var_export($result);
```

## CURL类

一个轻松调用CURL的类

友好地支持get、post方法，以及支持自定义curl请求

目前问题：

- exec参数设置不友好，需要自己记住参数，参数设置上跟调用原生的并没有多少区别

- 没有注释

```
$curl = new Curl();
$baiduHtml = $curl->get('http://www.baidu.com');
echo $baiduHtml;
```


<style>
.markdown-body pre>code{
    white-space: pre-wrap;
    word-break:break-all;
}
</style>
