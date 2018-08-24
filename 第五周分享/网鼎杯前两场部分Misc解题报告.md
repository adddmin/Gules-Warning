> ### Ps:这两天跟上Gules-WarningCTF 战队的多位大佬有幸打了两场的网鼎CTF，感触略深，其实感触最多的还是自己的菜鸡技术与众位大佬相比太过于薄弱。将来必定会潜心学习。
# 相关的Misc题解总结如下;
**笔记作者：博雅**

![image](http://ae01.alicdn.com/kf/HTB17QB0BZyYBuNkSnfo763WgVXaC.png)
## 0x01 Misc-套娃
![image](http://ae01.alicdn.com/kf/HTB1AbDTKb1YBuNjSszh763UsFXaU.png)

打开之后，直接丢入binwalk中进行查询。初步发现第六张图片存在问题。
### 关于LSB隐写
**LSB全称least significant bit，最低有效位
PNG文件中的图像像数一般是由RGB三原色（红绿蓝）组成，每一种颜色占用8位，取值范围为0x00~0xFF，即有256种颜色，一共包含了256的3次方的颜色，即16777216 种颜色
人类的眼睛可以区分约1000万种不同的颜色
这意味着人类的眼睛无法区分余下的颜色大约有6777216种
LSB隐写就是修改RGB颜色分量的最低二进制位（LSB），而人类的眼睛不会注意到这前后的变化
每个像数可以携带3比特的信息**
![image](http://ae01.alicdn.com/kf/HTB1hSnhB8yWBuNkSmFP760guVXaP.png)

**使用相关的LSB脚本进行解题。**

![image](http://ae01.alicdn.com/kf/HTB1.RvxKh9YBuNjy0Ff760IsVXaK.png)
**解得Flag。相关FLag保存在flag.txt文档中，直接打开查看即可。**
## 0x02 Misc-minified 
**使用用Stegsolve 打开图片：**
![image](http://ae01.alicdn.com/kf/HTB1XDPOKkCWBuNjy0Fa760UlXXaG.png)

**打开 Stegsolve 选择Data Extract 查看图片通道，如图，**

![image](http://ae01.alicdn.com/kf/HTB1FFLTKkOWBuNjSspp760PgpXaV.png)

**选择0 通道发现是LSB 隐写。其实与上面套娃的判断方式相同，由此判断是LSB隐写**
![image](http://ae01.alicdn.com/kf/HTB1VGBbdjfguuRjy1ze7620KFXaZ.png)

**分别把 alpha green和blue的0通道另存为再进行异或处理 最终在alpha 和green 中发现flag**
![image](http://ae01.alicdn.com/kf/HTB1UXqpB5OYBuNjSsD4762SkFXaD.png)

## 0x03 Misc-Clip
**下载题目是Disk 文件。第一反应是linux虚拟硬盘。
用winhex 打开如图：**

![image](http://ae01.alicdn.com/kf/HTB11YBbdi6guuRjy1Xd761AwpXaY.png)

**在winhex中第196280 发现了png的头文件如图：**

![image](http://ae01.alicdn.com/kf/HTB1eLXzKXmWBuNjSspd762ugXXac.png)
png 16进制文件头以 89504E47开头
**第一张图片：**
![image](http://ae01.alicdn.com/kf/HTB1YqwzKhGYBuNjy0Fn7605lpXac.png)
**第二张图片：**
![image](http://ae01.alicdn.com/kf/HTB1eYVadi6guuRkSnb4762u4XXae.png)
**使用PS拼接如下如：**
![image](http://ae01.alicdn.com/kf/HTB1rmLxKh9YBuNjy0Ff760IsVXap.png)

> 来自Gules-WarningCTF战队
