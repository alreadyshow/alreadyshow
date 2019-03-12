git clone https://github.com/flyzy2005/ss-fly
yum install git
git clone https://github.com/flyzy2005/ss-fly
ss-fly/ss-fly.sh -i flyzy2005.com 1024
ssserver -c /etc/shadowsocks.json -d start
ss-fly/ss-fly.sh -bbr
sysctl net.ipv4.tcp_available_congestion_control

ss

下载ss-fly
git clone https://github.com/flyzy2005/ss-fly
安装ss-fly
ss-fly/ss-fly.sh -i flyzy2005.com 1024

其中flyzy2005.com换成你要设置的shadowsocks的密码即可（这个flyzy2005.com就是你ss的密码了，是需要填在客户端的密码那一栏的），
密码随便设置，最好只包含字母+数字，一些特殊字符可能会导致冲突。
而第二个参数1024是端口号，也可以不加，不加默认是1024~（举个例子，脚本命令可以是ss-fly/ss-fly.sh -i qwerasd，也可以是ss-fly/ss-fly.sh -i qwerasd 8585，后者指定了服务器端口为8585，前者则是默认的端口号1024，两个命令设置的ss密码都是qwerasd）


启动：/etc/init.d/ss-fly start
停止：/etc/init.d/ss-fly stop
重启：/etc/init.d/ss-fly restart
状态：/etc/init.d/ss-fly status
查看ss链接：ss-fly/ss-fly.sh -sslink
修改配置文件：vim /etc/shadowsocks.json

卸载
ss-fly/ss-fly.sh -uninstall


ssr

	
ss-fly/ss-fly.sh -ssr

Congratulations, ShadowsocksR server install completed!
Your Server IP        :你的服务器ip
Your Server Port      :你的端口
Your Password         :你的密码
Your Protocol         :你的协议
Your obfs             :你的混淆
Your Encryption Method:your_encryption_method
 
Welcome to visit:https://shadowsocks.be/9.html
Enjoy it!

启动：/etc/init.d/shadowsocks start
停止：/etc/init.d/shadowsocks stop
重启：/etc/init.d/shadowsocks restart
状态：/etc/init.d/shadowsocks status
 
配置文件路径：/etc/shadowsocks.json
日志文件路径：/var/log/shadowsocks.log
代码安装目录：/usr/local/shadowsocks


./shadowsocksR.sh uninstall


bbr 加速



ss-fly/ss-fly.sh -bbr
检测
sysctl net.ipv4.tcp_available_congestion_control    	返回 net.ipv4.tcp_available_congestion_control = bbr cubic reno
