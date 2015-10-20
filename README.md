# data-bag
基于 session 的跨页面数据传递

## 安装

```shell
    composer require five-say/data-bag
```

## 使用

```php
<?php

use FiveSay\DataBag\DataBag;

// 设置
DataBag::set('test', 666);

// 获取指定数据
$test = DataBag::get('test');
// 获取全部数据
$all = DataBag::all();
// 获取全部历史记录
$history = DataBag::history();
// 获取指定数据的历史记录
$testHistory = DataBag::history('test');

// 测试输出
var_dump($test);
var_dump($all);
var_dump($history);
var_dump($testHistory);
die;

```

