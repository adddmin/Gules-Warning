---
title: BugKu_CTF
categories: CTF
date: 2018-08-14 15:02:11
tags:
---
# RE题
## Easy_vb
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;OD中查看字符串：得MCTF{_N3t_Rev_1s_E4ay_}
    ![](https://i.imgur.com/NMumWx8.png)
<!-- more -->

## Re_1
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;查壳，检查地址随机化，拖入IDA中唯一的验证是一个比较字符串的操作，利用OD动态调试得到flag：      
    ![](https://i.imgur.com/Tr3T2mz.png)
    ![](https://i.imgur.com/bGs02Ka.png)

## 游戏过关
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;这种题就是给你dump用的，根据流程在下图中，标记为0的nop掉，标记为1的jmp。就可以了。
    ![](https://i.imgur.com/z9WDolU.png)
    ![](https://i.imgur.com/XScdgu1.png)
    ![](https://i.imgur.com/A17mFWl.png)
    ![](https://i.imgur.com/OewRG90.png)
    ![](https://i.imgur.com/tARfNHd.png)
    ![](https://i.imgur.com/icC2gDA.png)
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;也可以直接修改跳转地址。再次不做赘述。

## 逆向入门
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;吐槽一句，这和逆向好像并没有关系，就是在`<img src=""/>`引号中间填写原内容。还以为文件被加密了呢。
     ![](https://i.imgur.com/4AKLJ58.png)

## Love
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;准备工作不赘述，拖入IDA 观察流程，先接受Inpuut，然后经过Base64加密，最后经过位移变换(在原基础上加上其在数组中的索引的值)，最后和`e3nifIH9b_C@n@dH`比较。
     ![](https://i.imgur.com/EbHfbtl.png)
     ![](https://i.imgur.com/lYsFfYd.png)
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;解密如下：e3nifIH9b_C@n@dH-->e2lfbDB2ZV95b3V9-->{i_l0ve_you}

## Mountain climbing
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;准备工作不做赘述，观察程序流程，首先，程序先是利用伪随机数创建了二维数组。然后接收Input，检查Input长度等于19，加密Input。然后在刚刚生成的二维随机数组里面，找出一队和最大的数值。
    ![](https://i.imgur.com/JyknrwM.png)
    ![](https://i.imgur.com/6qvmK6I.png)
    ![](https://i.imgur.com/jNEiEKf.png)
    ![](https://i.imgur.com/2c0NkKN.png)

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;利用srand和rand，产生的都是伪随机数，我们在dword_423D78 = dword_41A138[101]下断，可以得到整个随机数数组。
```
[77],
[5628, 6232],
[29052,1558, 26150],
[12947,29926,11981,22371],
[4078, 28629,4665, 2229, 24699],
[27370,3081, 18012,24965,2064, 26890],
[21054,5225, 11777,29853,2956, 22439,3341],
[31337,14755,5689, 24855,4173, 32304,292,  5344],
[15512,12952,1868, 10888,19581,13463,32652,3409, 28353],
[26151,14598,12455,26295,25763,26040,8285, 27502,15148,4945],
[26170,1833, 5196, 9794, 26804,2831, 11993,2839, 9979, 27428,6684],
[4616, 30265,5752, 32051,10443,9240, 8095, 28084,26285,8838, 18784,6547],
[7905, 8373, 19377,18502,27928,13669,25828,30502,28754,32357,2843, 5401, 10227],
[22871,20993,8558, 10009,6581, 22716,12808,4653, 24593,21533,9407, 6840, 30369,2330],
[3,    28024,22266,19327,18114,18100,15644,21728,17292,8396, 27567,2002, 3830, 12564,1420],
[29531,21820,9954, 8319, 10918,7978, 24806,30027,17659,8764, 3258, 20719,6639, 23556,25786,11048],
[3544, 31948,22,   1591, 644,  25981,26918,31716,16427,15551,28157,7107, 27297,24418,24384,32438,22224],
[12285,12601,13235,21606,2516, 13095,27080,16331,23295,20696,31580,28758,10697,4730, 16055,22208,2391, 20143],
[16325,24537,16778,17119,18198,28537,11813,1490, 21034,1978, 6451, 2174, 24812,28772,5283, 6429, 15484,29353,5942],
[7299, 6961, 32019,24731,29103,17887,17338,26840,13216,8789, 12474,24299,19818,18218,14564,31409,5256, 31930,26804,9736]
```
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;然后从中找和最大的，哈哈，广度优先搜索。可是我不会啊！！！接下来讲讲思路吧。

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;假设得到了xxxxx是我们的flag，然后要带到加密函数里面，其实这个加密函数是可以不需要了解算法的。反正我当初就没了解算法，带入两组测试码:RVRVRVRVRVRVRVRVRVR LHLHLHLHLHLHLHLHLHL,**可以知道V在偶数位上可以转化为R，L在奇数位上可以转化为H。**


## Take the maze
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;首先查壳，发现是VC，然后检查地址随机化，存在修改字节码即可。

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;拖入IDA中，浏览一下程序的主体流程：如下，首先接收输入，然后判断输入的长度，字符串长度必须要为24.接下来，对字符串的第16位与1进行异或，然后加密Input。加密方法暂时不做分析，我们往下看，验证加密后的Input的格式为数字`0-9,a-f`。最后调用`check函数`做最后一遍约束。然后打印png。
    ![](https://i.imgur.com/obezW0p.png)
    ![](https://i.imgur.com/4rKFuhw.png)

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;根据题目maze，我们知道这是一道迷宫问题。首先查看check的信息。首先将input的奇数位存入v7，然后利用switch，分派给v11，其实我们可以发现v11作为数组byte_541168的索引，我们还发现byte_541168的数值又被swich分派出去了，考虑这是一个maze问题，又因为switch分支有四个，可以推测这四个函数是关于控制移动方向的函数。
     ![](https://i.imgur.com/axNVUZ1.png)

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;接下来我们来验证猜测是否正确。在循环的循环增量可以看到迷宫的列数为26，方向是向下。if判断可以看出来，移动的范围<=10。如果需要移动需要满足3个条件。最重要的是`dword_540548[1]^dword_540068[i]==0`。
     ![](https://i.imgur.com/Kdnz6Ha.png)

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;结合控制四个方向的函数，可以知道：迷宫范围是26*x(x暂时未知)。每步移动的范围是：上下：1-10，左右：1-24。
    ![](https://i.imgur.com/KW4Bppp.png)
    ![](https://i.imgur.com/4bmRMPy.png)
    ![](https://i.imgur.com/1uVzuEs.png)

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;根据上述分析，我们知道四个函数表示迷宫中四个方向的路线图，只需要满足上述三个条件即可移动，到这里需要写个IDC脚本，来dump我们需要知道的路径。**好好学IDC，感觉这玩意很厉害**
    ![](https://i.imgur.com/MjjDiIr.png)
    ![](https://i.imgur.com/qiTACde.png)
    ![](https://i.imgur.com/wZWhZMH.png) 
    ![](https://i.imgur.com/mjueQL1.png)
    ![](https://i.imgur.com/0MKIaRI.png)
    ![](https://i.imgur.com/8z3M9Ws.png)


&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;接下来确定迷宫出口，我们知道迷宫宽26，311=11X26+25，**也就说是从迷宫左上角到右下角**
    ![](https://i.imgur.com/l8aH3KQ.png)

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;根据四个方向的路径，我们结合迷宫出入口，画出路线图。
    ![](https://i.imgur.com/7EB5gA3.png)

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;结合1之前提到的，方向：0:下 2:左  3:右  4:上。移动范围：56789abcdef:表示每个方向重复的次数0123456789。得到`06360836063b0839073e0639`。

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;考虑到输入之后存在一个加密函数，所以，刚刚我们得到的并不是flag。我们输入伪码`0123456789ABCDEFGabcdefg`，经过Encode函数。加密之后`0123456789`得到的是`0000000000`，为什么会这样，思考一下，为啥两个数运算之后为0，答案是在两个相同的数(字符)异或后得0，所以加密方法是`^auto(i)[0-22]`。
     ![](https://i.imgur.com/Gu5cOw4.png)

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;写出脚本，如下：得到flag：`07154=518?9i<5=6!&!v$#%.`
```
int main(void)
{
	char str[]="06360836063b0839073e0639";
	str[16]^=1;
	for(int i=0;i<strlen(str);i++)
		str[i]^=i;
	puts(str);	
	return 0; 
} 
```
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;输入刚刚得到input，程序打印出来一个二维码，扫扫得到：(反正就是提示你，在flag后面加上“Docupa”)，最后得到flag：07154=518?9i<5=6!&!v$#%.Docupa


