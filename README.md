# Twnic IP

![tests](https://github.com/MilesChou/twnicip/workflows/tests/badge.svg)
[![codecov](https://codecov.io/gh/MilesChou/twnicip/branch/master/graph/badge.svg?token=OPzYQj42bQ)](https://codecov.io/gh/MilesChou/twnicip)
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/4f82ecb7dd12478cba9b51c8bb26d74e)](https://www.codacy.com/gh/MilesChou/twnicip/dashboard)

檢查是否為台灣 IP 的小工具，台灣 IP 沒想像中多，所以直接把找到的資料轉成 PHP 原生變數型態的「[資料庫](/src/Database.php)」，再使用另一個 [Facade](/src/TwnicIp.php) 做搜尋。

原則上 Database 是不能修改的，只能靠原 repo 更新。

## TwnicIp

這是主要驗證 IP 來源的 class，使用下面三個方法來確認是否是台灣 IP：

```php
$twnicIp = new TwnicIp();

$twnicIp->isTaiwan('202.39.128.1'); // isTaiwanByIp() 的別名
$twnicIp->isTaiwanByIp('202.39.128.1');
$twnicIp->isTaiwanByLong(3391586305); // 轉成 IP 即 202.39.128.1 
```

如果有新加入的 IP，但原始 repo 沒有更新時，可以自行新增：

```php
$twnicIp->includeRange('127.0.0.1', '127.0.0.1');

$twnicIp->isTaiwan('127.0.0.1'); // return true
```

同理，如果某個區段的 IP 需要被排除時，也可以自行處理：

```php
$twnicIp->excludeRange('127.0.0.1', '127.0.0.1');

$twnicIp->isTaiwan('127.0.0.1'); // return false
```

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
