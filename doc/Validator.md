### 工具描述

Validator类

### 工具路径

`system/core/utils/Validator.php`

### 函数列表

```
rules( $data, $rules, $option = null )
```

**参数说明**

- `data` 数据，比如表单格式的数组

- `rules` 规则，备注详细说明

- `option` 配置项，支持配置：

`debug` 是否开启debug模式，如果不开启，一旦验证失败，就会直接返回

`showField` 是否显示字段说明，如果开启，则会在data返回数组中，加入一个field数组，返回所有的需要提交的字段的说明

以上两个参数开关依赖系统的DEBUG常量

`safe` 安全模式，默认为true，开启，则只有在规则列表里的字段会被通过，其他的全部被删除

 **返回示例**
成功时：
```
array(
    `data` => array(),//过滤后的数据
);
```
失败时：
```
array(
    `error` => array(),//错误的提示
);
```

### 备注

一条规则的格式

```
array( [field], [validator], [msg],... )

```

[field] 键为0，必填，数组或者逗号字符串，字段列表，如果是数组，索引数组会把值作为字段，键值对会把键作为字段，并且把值作为返回field里的字段说明，field见rules的showField参数说明，逗号字符串转化成数组后处理

[validator] 键为1，必填，验证器或者过滤器，现在已经有对应的类常量，IDE可以直接获取提示

[msg] 键为2，选填，如果错误，返回的错误提示

验证器和过滤器说明

以`UID`符号代表当前用户uid

#### 验证器

验证器在返回false的时候会得到错误，true的时候通过

- mobile：手机号验证器，为空时不做验证，isEmpty（选填）
```
array( array( 'mobile' => '手机号' ), 'mobile', 'isEmpty' =>function( $data ){ return empty( $data ); } )，或者是isEmpty => 'empty'
```
- email：邮箱验证器，为空时不做验证，isEmpty（选填）
```
array( array( 'email' => '邮箱' ), 'email', '邮箱格式不正确' )
```
- required：必填验证器，空时返回，isEmpty（选填）
```
array( array( 'name' => '名字' ), 'required' )
```
- unique：唯一验证器，model（必填），attribute（必填），extra（选填）
```
array( array( 'userid' => 'UID' ), 'model' => User::model(), 'attribute' => 'uid', 'extra' => " isdel != '1' " )
```
- match：正则验证器，为空时不做验证，pattern（必填），isEmpty（选填）
```
array( array( 'mobile' => '手机号' ), 'pattern' => '/^1\\d{10}$/' )
```
- rule：条件验证器，使用filter过滤器处理函数，method（必填），condition（选填）
```
当前用户是张三时，name必须等于张三
array( array( 'name' => '名字' ), 'rule', 'method' => function( $data ) { return $data ==  '张三'； }， 'condition' => function( $data ) { UID == '1';//张三的UID为1 } )
```
- in：范围验证器，list（必填），useStrict（选填）默认false，value（选填）有值时使用default过滤器设置默认值
```
array( array( 'type' => '类型' ), 'in', 'list' => array( 0, 1, 2 ), 'useStrict' => true )
效果等价与 in_array( 'type', array( 0, 1, 2 ), true )
```

- array：数组验证器，list（必填），当目标数组的键都在列表里或者数组为空时，验证通过
```
如果attach不为空，并且不是数组或者不包含list中指定的键，那么返回失败
array( array( 'attach' => '附件数组' ), 'array', 'list' => array( 'id', 'url', 'name' ) )
```
- arrays 二维数组验证器，list（必填），同上，不过是验证二维数组
```
attach是多个附件的列表
array( array( 'attach' => '附件数组' ), 'array', 'list' => array( 'id', 'url', 'name' ) )
```

#### 过滤器

过滤器按照规则设置字段的值，处理之后重新赋值给字段

- default：为空时设置默认值，value（必填）默认值，isEmpty（选填）函数或者函数结构，true则为空
```
array( 'id', 'default', 'value' => 0 )
```

- filter：使用函数或者函数结构处理值，method（必填），value（选填）有值时使用default过滤器设置默认值
```
注意，如果给了默认值，在取得默认值后并不会继续执行method函数
array( 'time', 'filter', 'method' => 'strtotime', 'value' => time() )
```

- set：直接设置值，value（必填）
```
array( 'uid', 'set', 'value' => UID )
```

- encode：不为空时进行html字符串转码，isEmpty（选填）
```
array( array( 'content'=> '内容' ), 'encode' )
```

- unset：使用filter过滤器判断是否unset，true时删除键，value（选填）有值时使用default过滤器设置默认值
```
如果mobile字段为空，则提交的数据里删除这个键
array( array( 'mobile' ), 'unset', 'method' => 'empty' )
```

其他参数列表：

- isEmpty：空时为true，函数或者回调结构，可以更改判断为空的依据，默认null才认为是空的

- model：模型类

- attribute：表的字段名

- extra：额外的条件，会用AND附加到`attribute` = 'fieldValue'上

- value：设置的值

- method：函数或者回调结构

- pattern：正则表达式，可以没有值，默认为'//'，但必须要有这个键

- condition：rule验证器里，返回true时，才会进行method验证，false时，表示条件不成立，就不需要验证，不填时，必定进行method验证

- list：in验证器的范围列表，使用default过滤器设置默认值

- useStrict：是否使用严格模式，如果否，则会在一些函数里强制转化数据类型

### 变更日志

#### 2016.10.10

- 创建此类

#### 2016.10.11

- 添加match过滤器

#### 2016.10.12

- 添加unset过滤器

#### 2016.10.13

- isEmpty、method等函数类型的参数支持`empty`（在PHP里empty并不是一个函数，只是看起来像：`empty( $var )`）

- 修复filter过滤器行为：如果有设置value（默认值），不再执行method，不然结果无法预测

- 修改rules函数的第三个参数为配置项，说明如上

- 修复unique验证器，没有额外条件时，语句错误的问题

- 添加in验证器

#### 2016.10.17

- 修复unset过滤器强制把存在的值删除的问题

- in验证器、unset过滤器使用default过滤器添加默认值

- 修改mobile、email、match验证器的行为：为空时不做验证

- 添加安全模式，详细见rules函数第三个参数（配置项）

- 添加过滤器和验证器常量，调用更方便：比如`Validator::VALIDATOR_MOBILE`代替`mobile`

- 添加格式化好的验证器和过滤器参数列表以及说明，Netbeans下可以通过列表里看到，文档生成工具应该也可以按照格式生成文档

#### 2016.10.20
- 添加自定义函数return、empty，因为PHP里empty并不是函数，所以放在method参数里会出错，现在可以支持method参数，return函数直接返回结果，不做任何处理，目的是为了在field列表里显示或者在安全模式下可以通过检查

#### 2016.10.21

- 添加array、arrays验证器

#### 2016.11.11

- 修复默认值获取错误导致无法设置默认值的问题