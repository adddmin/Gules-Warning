## \`\`\`\`\# 简单android逆向

手机安装不上... 上模拟器  
![](/assets/2018-08-19 21_56_21-启动.png)

大概是个输入密码的逆向。。。  
上工具 android killer 不好使，本地没搭建android开发环境，想从log看不啥  
换用的Jeb 可以下载52破解的工具包

![](/assets/20180819220404.png)

我在图上已经写明白了，因为之前学过一点android

![](/assets/20180819221602.png)

来看一下 check\(\) 方法

![](/assets/20180819221839.png)

现在写一个简单的Java类

```java
package demo;

import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;
import java.util.Formatter;

public class Test {

    private String bytes2hexstr(byte[] bytes) {
        StringBuffer v2 = new StringBuffer(bytes.length * 2);
        Formatter v1 = new Formatter(((Appendable) v2));
        int v5 = bytes.length;
        int v3;
        for (v3 = 0; v3 < v5; ++v3) {
            v1.format("%02x", Byte.valueOf(bytes[v3]));
        }
        v1.close();
        return v2.toString();
    }

    private void check(String user) {
        MessageDigest v2;
        try {
            v2 = MessageDigest.getInstance("MD5");
            v2.reset();
            v2.update(user.getBytes());
            String v0 = this.bytes2hexstr(v2.digest());
            StringBuilder v4 = new StringBuilder();
            int v1;
            for (v1 = 0; v1 < v0.length(); v1 += 2) {
                v4.append(v0.charAt(v1));
            }
            System.out.println(v4.toString());
        } catch (NoSuchAlgorithmException e) {
            e.printStackTrace();
        }

    }

    public static void main(String[] args) {
        Test t = new Test();
        t.check("admin");

    }

}
```

现在输出

![](/assets/2018-08-19 22_21_05-启动.png)

这便是密码

