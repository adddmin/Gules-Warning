题目：http://120.24.86.145:8002/qiumingshan/
</br>可以看出是一个编程题目，需要使用脚本计算。
## 目标
1. 第一步，用python读取网页内容
2. 第二步，用正则提取出需要计算的式子并计算
3. 第三步，post发送结果，接收flag

## 具体实现
我们先来看第一步：如何读取网页内容？当然要利用requests库了。</br>
要注意的是，因为每次刷新计算式都会变化，所以我们要用到Session功能。

```
url = 'http://120.24.86.145:8002/qiumingshan/'
ctf = requests.Session()
htmlSource = ctf.get(url)
```
然后是第二步：提取计算式。</br>
关于正则，我们可以使用re这个模块。

```
re.search(pattern, string, flags=0) //扫描字符串，寻找的第一个由该正则表达式模式产生匹配的位置，并返回相应的MatchObject实例。
group([group1, ...])//返回Match对象的一个或多个子组。
```
首先使用search匹配相应字符串，再用group返回之。

```
exp = re.search(r'(\d+[+\-*])+(\d+)',source.text).group() 
```
剩下还有的就是计算了，直接使用eval()函数得出结果。

```
result = eval(exp)
```
最后进行第三步：发送计算结果，获取flag。</br>
使用requests.post()发送数据。

```
payload = {'value': result} 

print(ctf.post(url, data = payload).text)
```
## 完整脚本

```
#！/usr/bin/python

import requests
import re

url = 'http://120.24.86.145:8002/qiumingshan/'
ctf = requests.Session()
htmlSource = ctf.get(url)

exp = re.search(r'(\d+[+\-*])+(\d+)',htmlSource.text).group() 
result = eval(exp)
payload = {'value': result} 

print(ctf.post(url, data = payload).text) 

```
