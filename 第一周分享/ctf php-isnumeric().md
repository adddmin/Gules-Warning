# PHP is_numerie()

---

从一道CTF题目谈到一个php 函数  
[题目地址](http://120.24.86.145:8002/get/index1.php "题目地址")

```php
<?php
    $number = $_GET["num"]; // 1%00
     if(!is_numeric($number)){
        echo $number."<br />";
        var_dump($number); // string(2) "1" 
        //==是比较运算符号 不会检查条件式的表达式的类型
        if($number==1){
            echo "flag1";
        }
        //===是恒等计算符 同时检查表达式的值与类型

        var_dump('1'); // string(1) "1" 
        if($number==='1'){
            echo "flag2";
        }
    }
```

php 手册中对此函数的介绍

> bool **is\_numeric**\( [mixed](mk:@MSITStore:C:\Users\Administrator\Desktop\php_manual.chm::/res/language.pseudo-types.html#language.types.mixed)`$var` \) 检测变量是否为数字或数字字符

此函数可对十进制 八进制 十六进制 数进行判断

```php
$number1 = 1;
$number2 = 01;
$number3 = 0x01;
echo is_numeric($number1); // 1 
echo is_numeric($number2); // 1
echo is_numeric($number3); // 1
```

那么我们看一下此题的源码

```php
$num=$_GET['num'];
if(!is_numeric($num))
{
echo $num;
if($num==1)
echo 'flag{**********}';
}
```

当看起了此题有一个矛盾，`$num` 必须是一个字符 但是又必须是一个数字 如何进行绕过了

首先我们要了解一下 PHP中运算符

> == 是比较运算符号 不会检查条件式的表达式的类型  
> === 是恒等计算符 同时检查表达式的值与类型

就是说我们只需 `"1"=1` 就可以绕过`==` 但是无法绕过`===`

### 如何绕过if

我们需要了解URL编码，向其中填装`%00` 无意义的字符 和 `%20` 空格 就可以让`1%00`变成一个字符串。从而绕过  
这样我们的输入的`1%00` 变成`string(2) "1"` 又可以绕过第二个判断

### 总结

1. URL 编码
2. php中 == ===的区别
3.  is\_numeric\(\) 对可以判断的类型
