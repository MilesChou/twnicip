# Twnic IP

![tests](https://github.com/MilesChou/twnicip/workflows/tests/badge.svg)
[![codecov](https://codecov.io/gh/MilesChou/twnicip/branch/master/graph/badge.svg?token=OPzYQj42bQ)](https://codecov.io/gh/MilesChou/twnicip)
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/4f82ecb7dd12478cba9b51c8bb26d74e)](https://www.codacy.com/gh/MilesChou/twnicip/dashboard)
[![Latest Stable Version](https://poser.pugx.org/MilesChou/twnicip/v/stable)](https://packagist.org/packages/MilesChou/twnicip)
[![Total Downloads](https://poser.pugx.org/MilesChou/twnicip/d/total.svg)](https://packagist.org/packages/MilesChou/twnicip)
[![License](https://poser.pugx.org/MilesChou/twnicip/license)](https://packagist.org/packages/MilesChou/twnicip)

檢查是否為台灣 IP 的小工具。

台灣 IP 沒想像中多，這個工具是直接把找到的資料轉成 PHP 原生變數型態的「[資料庫](/src/Database.php)」，再使用另一個 [Facade](/src/TwnicIp.php) 做搜尋。

原始 Database 是不能修改的，只能靠原 repo 更新，而 Facade 層則是可以動態標記 IP 是否為台灣 IP。

## Installation

透過 Composer 即可安裝：

```bash
composer require mileschou/twnicip
```

## Usage

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

私有網域狹義來說，並不屬於台灣的 IP。而實務上，機器在台灣，某種程度也是算台灣的 IP 啦！

如果想把私有網域當作台灣 IP 的話，可以額外呼叫下面這個方法：

```php
$twnicIp->includePrivateIp();
```

裡面其實只是呼叫 `includeRange()` 把私有 IP 的 range 加入。

## References

* [Twnic IP 列表](https://www.twnic.tw/download/IP/main_f3.htm) - 但此文件看起來已過時，因此改採用其他資料庫
* [IP2LOCATION Lite](https://lite.ip2location.com/) - 免費且完整的資料庫，目前是使用這個

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
