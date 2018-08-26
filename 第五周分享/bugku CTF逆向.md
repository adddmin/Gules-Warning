# 来自BugKu的两道简单逆向

---

网鼎杯被虐到了

## 逆向入门

下载得到一个admin.exe 运行不了，很奇怪 ida打开也不对劲，无法反编译。用十六进制打开

![](https://i.imgur.com/a3yEsQq.png)

发现是一个base64图片

![](https://i.imgur.com/zGFo37c.png)

扫码得flag


## vb 逆向

![](https://i.imgur.com/Kakg25e.png)

... 打开52破解的VB Decompiler

没解什么算法 到是出来一个flag

![](https://i.imgur.com/jbZ0o23.png)

## RE
下载得到一个可执行文件
运行了一下要求输入一个字符串

![](https://i.imgur.com/LuRFOed.png)

用ida逆向后无头绪 使用OD调试

![](https://i.imgur.com/kF3GtgP.png)

F7 单步出flag

![](https://i.imgur.com/TdegFsM.png)
