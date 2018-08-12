提示说是SQL约束攻击，那就看下什么叫SQL约束攻击

## 基于约束条件的SQL攻击
我们先看一下攻击场景：在用户注册处，利用SQL约束攻击便可以登陆任意用户账号。</br>
下面是一段用户注册的代码：

```
<?php 
 
$username = mysql_real_escape_string($_GET['username']); 
$password = mysql_real_escape_string($_GET['password']); 
$query = "SELECT *  
          FROM users  
          WHERE username='$username'"; 
$res = mysql_query($query, $database); 
if($res) {  
  if(mysql_num_rows($res) > 0) { 
    // 存在此用户，注册失败 
    . 
    . 
  } 
  else { 
    // 不存在此用户，注册成功
    $query = "INSERT INTO users(username, password) 
              VALUES ('$username','$password')"; 
    . 
    . 
  } 
}  
```
可以看出，注册时的逻辑为：**先判断用户名是否存在，再进行注册**。
</br>下面是一段验证登陆的代码：

```
<?php 
$username = mysql_real_escape_string($_GET['username']); 
$password = mysql_real_escape_string($_GET['password']); 
$query = "SELECT username FROM users 
          WHERE username='$username' 
              AND password='$password' "; 
$res = mysql_query($query, $database); 
if($res) { 
  if(mysql_num_rows($res) > 0){ 
      $row = mysql_fetch_assoc($res); 
      return $row['username']; 
  } 
} 
return Null
```
登陆的逻辑为：**比较用户名和密码是否符合，然后进行登陆**。</br>
接下来要谈论几个知识点，也是差异。
1. SQL语句里字符串末尾空格会被删除。也就是说，在它们里面，"Adan0s"和"Adan0s         "是一样的，比如在WHERE子句或INSERT语句中。
2. 在INSERT查询中，如果你输入超过最大限制长度的字符串，那么它仅会读取在限制内的那部分字符串。例如最大长度为10位，你输入15位，那么后5位将被丢弃。

## 漏洞原理
假设现在你得知有一个用户名为admin的用户，且注册处存在SQL约束漏洞，那么你可以在注册处用户名栏里输入"admin[大量空白字符]1"加任意密码来登陆admin用户。</br>
也许你还有点糊涂，那么我们现在一步一步地运行流程看看。</br>
1. 注册时的SQL语句

```
SELECT * FROM users WHERE username='$username'
//查询是否已被注册

INSERT INTO users(username, password) VALUES ('$username','$password')
//注册成功，插入信息
```

你的"admin[大量空白字符]1"会注册成功，因为在SELECT语句里，"admin[大量空白字符]1"与"admin"不相等。**但是！**在插入信息时，会因为INSERT的特性将后面的字符串"[空白字符]1"丢弃，即插入"admin[空白字符]"。

</br>当你搜索用户名"admin"时，会出现两条结果，如下：

```
+-------------------------+-------------+ 
| username                | password    | 
+-------------------------+-------------+ 
| admin                   | password1   | 
| admin                   | password2   | 
+-------------------------+-------------+ 
```
第二个其实是"admin[空白字符]"。

2. 登陆时的SQL语句：

```
SELECT username FROM users WHERE username='$username' AND password='$password'
//查询用户名和密码
```
前面说到，查询用户名"admin"会出现两条结果。当使用"admin"和"password2"登陆时，也会登陆成功，原因是"admin"和"admin[空白字符]"是相等的。

至此，攻击完成。