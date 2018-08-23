---
title: 网鼎杯CTF
categories: CTF
date: 2018-08-21 14:02:11
tags:
---
# 前言
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;第一次和博雅他们参加全国性的CTF比赛，学到了很多。算了算了，我就是想凑个前言出来，不编了不编了。**Z3和angr这两个工具要好好学。**
<!-- more -->

# 第一场——青龙之战
## Re题
### beijing
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;这道题就是在考脑洞的，elf，定位到函数主函数。流程很简单，将全局变量传入sub_8048460中，然后打印。
    ![](https://i.imgur.com/7ewbzDQ.png)

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;查看sub_4048460,流程也很简单，根据传入参数的不同利用switch进行不同的分支处理，每个分支都是异或运算。
    ![](https://i.imgur.com/aupJEjr.png)

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;我们可以看到在异或的右侧对应的变量是a,g,.....，懒得计算顺序，根据单词拼起来就是flag{amazing_beijing}.

### Advance
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;执行程序，依然没有输入，可以看到一些hex数值。可以猜测应该是解密先是的hex数据。
     ![](https://i.imgur.com/zCWu7qp.png)

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;首先，将hex转化为ASCII编码。得到如下数据`K@LKVHr[DXEsLsYI@\AMYIr\EIZQ`.
     ![](https://i.imgur.com/pZNtdcJ.png)

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;对前面的数交叉引用，发现TLS函数。得到加密逻辑    
    ![](https://i.imgur.com/og06XBi.png)

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;编写如下脚本,得到flag：flag{d_with_a_template_phew}
    ![](https://i.imgur.com/DGj8mRg.png)

```
tmp = 'K@LKVHr[DXEsLsYI@\AMYIr\EIZQ'
flag = ""
for i in range(len(tmp)):
    if i % 2 == 0:
        flag += chr(ord(tmp[i])^0x2D)
    else:
        flag += chr(ord(tmp[i])^0x2C)
print flag
```

 

## Misc题
### minified
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;一看就知道是一道隐写题。利用Stego打开，测试一下不同的通道的图像。可以发现在A0处无正常图片，可以判断是0通道有问题。
     ![](https://i.imgur.com/gId4j2G.png)

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;分别提取0通道的四张图片。这里提取通道是**在主页面提取的，而不是在Analyse处提取**。利用file--> save as保存。
     ![](https://i.imgur.com/ZKnNESV.png)

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;最后将打开A0.png，然后Analyse-->Image Combiner,在异或显示出flag。
    ![](https://i.imgur.com/6wJ0dRz.png)

# 第二场——白虎之战
## Re题
### give a try
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PE文件，查壳，查看地址随机化并没有发现异常。

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;拖入IDA中，发现主流程函数如下：首先判断字符串长度是否为42，接着利用随机数与输入进行乘积取模运算，最后与特定值进行比较。
    ![](https://i.imgur.com/1VA7Mpq.png)
    ![](https://i.imgur.com/JseeXDr.png)
    ![](https://i.imgur.com/C0dJ6YB.png)

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;首先，我们知道利用srand和rand产生的随机数并不是真正的随机数，只要传递给srand的随机数种子是确定的，所产生的随机数在每次运行时都是确定的，这个叫做**伪随机数。**这就奠定了可以使用爆破的方式求解

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;第二点，我们知道dword_40406c处是作为随机数种子传入srand，做交叉引用，发现两处写入操作，判断是使用了**TLS技术**，tls在函数线程执行之前可以获得优先执行的权利，一般用于反调试，也可以用于数据的保护。这里利用CE查的原始数据为：**0x31333359 ^ 3681**
     ![](https://i.imgur.com/R3kSvVP.png)
     ![](https://i.imgur.com/9RhNItW.png)

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;最后一点是：由于IDA的f5反编译的差距较大，关于这道题利用内联汇编的形式写脚本。
```
unsigned int fun(int a1,int a2);

int main(void)
{
	srand(0x31333359 ^ 3681);    //初始化随机数种子
	int dword_4030B4[42] = {0x63B25AF1,0x0C5659BA5,0x4C7A3C33,0x0E4E4267, 0x0B611769B,
		0x3DE6438C, 0x84DBA61F,0x0A97497E6, 0x650F0FB3, 0x84EB507C,
		0x0D38CD24C,0x0E7B912E0, 0x7976CD4F, 0x84100010, 0x7FD66745,
		0x711D4DBF, 0x5402A7E5, 0x0A3334351, 0x1EE41BF8, 0x22822EBE,
		0x0DF5CEE48, 0x0A8180D59, 0x1576DEDC, 0x0F0D62B3B, 0x32AC1F6E,
		0x9364A640, 0x0C282DD35, 0x14C5FC2E, 0x0A765E438, 0x7FCF345A,
		0x59032BAD, 0x9A5600BE, 0x5F472DC5, 0x5DDE0D84, 0x8DF94ED5,
		0x0BDF826A6, 0x515A737A, 0x4248589E, 0x38A96C20, 0x0CC7F61D9,
		0x2638C417, 0x0D9BEB996 };
	int rand_table[42] = { 0 };        //初始化随机数表
	for (int i = 0; i < 42; i++)
	{
		rand_table[i] = rand();        //生成随机数表
		printf_s("%d\n", rand_table[i]);
		printf_s("--------\n");
	}

	for (int j = 0; j < 42; j++)      //遍历次数
	{
		for (int i = 32; i < 128; i++)     //遍历数值
		{
		//	printf_s("%d\n", fun(rand_table[j], i));			
			if (fun(rand_table[j], i) == dword_4030B4[j])
				printf("%c", i);
		}
			
	}
	system("pause");
	return 0;
}

unsigned int fun(int a1, int a2)
{
	__asm {
		mov     eax, dword ptr[ebp + 8]      //第一个参数
		movzx   ecx, byte ptr[ebp + 12]      //第二个参数
		mul     ecx
		mov     ecx, 0FAC96621h
		push    eax
		xor     edx, edx
		div     ecx
		pop     eax
		push    edx
		mul     eax
		div     ecx
		mov     eax, edx
		mul     edx
		div     ecx
		mov     eax, edx
		mul     edx
		div     ecx
		mov     eax, edx
		mul     edx
		div     ecx
		mov     eax, edx
		mul     edx
		div     ecx
		mov     eax, edx
		mul     edx
		div     ecx
		mov     eax, edx
		mul     edx
		div     ecx
		mov     eax, edx
		mul     edx
		div     ecx
		mov     eax, edx
		mul     edx
		div     ecx
		mov     eax, edx
		mul     edx
		div     ecx
		mov     eax, edx
		mul     edx
		div     ecx
		mov     eax, edx
		mul     edx
		div     ecx
		mov     eax, edx
		mul     edx
		div     ecx
		mov     eax, edx
		mul     edx
		div     ecx
		mov     eax, edx
		mul     edx
		div     ecx
		mov     eax, edx
		mul     edx
		div     ecx
		mov     eax, edx
		pop     edx
		mul     edx
		div     ecx
		mov eax, edx
	}
}
```
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;执行的结果如下：flag{h3r3_th3r3_i5_@_w1ll-th3r3_i5_@_w4y}
     ![](https://i.imgur.com/PRaHGlk.png)

### Martricks
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp利用IDA，可以看到流程。如图，我们可以发现存在两个提示，一个正确一个错误，输入可以被限制是字符串(根据scanf格式就可以知道了)。既然是字符串，就可以使用angr暴力求解得到flag.
     ![](https://i.imgur.com/8hvcLwH.png)
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp成功路径find=0x400A84,失败路径：avoid=0x400A90,利用angr爆破代码如下：
```
import angr

def main():
    p = angr.Project("martricks")   //导入项目
    simgr = p.factory.simulation_manager(p.factory.full_init_state())
    simgr.explore(find=0x400A84, avoid=0x400A90)

    return simgr.found[0].posix.dumps(0).strip('\0\n')

if __name__ == '__main__':
print main()
```


## Misc
### 套娃
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;直接定位到最后一张图片，利用Stego查看，Alpha通道是白色的，说明可能是LSB隐写，我们利用Data Extract查看一下各个通道。
    ![](https://i.imgur.com/3mhJ4Aa.png)

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;因为之前在A通道是白色的，所以判断可能是RGB存在LSB隐写的可能性。先是进行如下设置。
    ![](https://i.imgur.com/FS3gHRV.png)

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;没有发现线索，修改一下Bit Plane Order(瞎试)。可以在BGR处发现flag
    ![](https://i.imgur.com/BpYlc5q.png)

### 虚幻
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;根据提示，这是一个汉信码，果然是官方的比赛就是这样任性。科普一下汉信码的图，以后要用到。
    ![](https://i.imgur.com/bdBuJCf.png)

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;首先题目给的和汉信码差距很大，binwalk(foremost)得到九张图片。按照foremost的命名顺序拼好。
    ![](https://i.imgur.com/Ce4exDV.png)

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;在利用Stego在R7处形成黑白图片。
    ![](https://i.imgur.com/f0VlFF8.png)

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;发现和汉信码还是有点差距(这个真的不好想)，对上述图片反色一下，得到
    ![](https://i.imgur.com/o1r8cow.png)

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;对比一下正常的汉信码，发现缺少4个角，PPT补上。
     ![](https://i.imgur.com/QJDK0KT.png)

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;利用[http://www.efittech.com/hxdec.aspx](http://www.efittech.com/hxdec.aspx)扫一下得到flag。
     ![](https://i.imgur.com/YxB1Yix.png)

