#Linux计划任务

在介绍 crontab 命令之前，首先要介绍一下 crond ，因为 crontab 命令需要 crond 服务支持。 crond 是 Linux 下用来周期地执行某种任务或等待处理某些事件的一个守护进程，和 Windows 下的计划任务有些类似。

crond 服务的启动和自启动方法如下：
重新启动：`service crond restart`
查看状态：`service crond status`
设置为开机启动：`chkconfig crond on`

其实，在安装完成操作系统后，默认会安装 crond 服务工具，且 crond 服务默认就是自启动的。crond 进程每分钟会定期检查是否有要执行的任务，如果有，则会自动执行该任务。

可以通过 `/etc/cron.allow` 和 `/etc/cron.deny` 文件来限制某些用户是否可以使用 crontab 命令
* 当系统中有 `/etc/cron.allow` 文件时，只有写入此文件的用户可以使用 crontab 命令，没有写入的用户不能使用 crontab 命令。同样，如果有此文件 `/etc/cron.deny` 文件会被忽略，因为 `/etc/cron.allow` 文件的优先级更高。
* 当系统中只有 `/etc/cron.deny` 文件时，写入此文件的用户不能使用 crontab 命令，没有写入文件的用户可以使用 crontab 命令。

`crontab -l`：显示某用户的 crontab 文件内容，如果不指定用户，则表示显示当前用户的 crontab 文件内容。
![](https://i.imgur.com/WG49uTk.png)
`crontab -e`：编辑某个用户的 crontab 文件内容。如果不指定用户，则表示编辑当前用户的 crontab 文件。
![](https://i.imgur.com/RyCMc2W.png)

这个文件中是通过 5 个 `*` 来确定命令或任务的执行时间的，这 5 个 `*` 的具体含义如下
* 第一个 `*` ：一小时当中的第几分钟（minute） 范围：	0~59
* 第二个 `*` ：一天当中的第几小时（hour）	  范围：0~23
* 第三个 `*` ：一个月当中的第几天（day）	  范围：1~31
* 第四个 `*` ：一年当中的第几个月（month）    范围：1~12
* 第五个 `*` ：一周当中的星期几（week）		  范围：0~7（0和7都代表星期日）


在时间表示中，还有一些特殊符号需要学习，如下所示。
* `*`（星号）：代表任何时间。比如第一个 `*` 就代表一小时种每分钟都执行一次的意思。
* `,`（逗号）：代表不连续的时间。比如 `0 8,12,16 * * *` 就代表在每天的 8 点 0 分、12 点 0 分、16 点 0 分都执行一次命令。
* `-`（中杠）：代表连续的时间范围。比如 `0 5 * * 1-6` ，代表在周一到周六的凌晨 5 点 0 分执行命令。
* `/`（正斜线）：代表每隔多久执行一次。比如 `*/10 * * * *` ，代表每隔 10 分钟就执行一次命令。

当 `crontab -e` 编辑完成之后，一旦保存退出，那么这个定时任务实际就会写入 /var/spool/cron/ 目录中，每个用户的定时任务用自己的用户名进行区分。而且 crontab 命令只要保存就会生效，只要 crond 服务是启动的。

我使用时遇到的一些问题：
设置计划任务之后不执行，把时间点后面的脚本单独复制出来执行没有问题。
`service crond status` 查看服务的状态，看看是否有报错。
它的错误日志都会放在 `/var/spool/mail/对应用户名`下，我的错误日志如下：

From root@izuf6hxtmn3a1egw9z21hjz.localdomain  Thu Oct 17 10:46:15 2019
Return-Path: <root@izuf6hxtmn3a1egw9z21hjz.localdomain>
X-Original-To: root
Delivered-To: root@izuf6hxtmn3a1egw9z21hjz.localdomain
Received: by izuf6hxtmn3a1egw9z21hjz.localdomain (Postfix, from userid 0)
	id B67D91202B4; Wed, 16 Oct 2019 18:54:01 +0800 (CST)
From: "(Cron Daemon)" <root@izuf6hxtmn3a1egw9z21hjz.localdomain>
To: root@izuf6hxtmn3a1egw9z21hjz.localdomain
Subject: Cron <root@izuf6hxtmn3a1egw9z21hjz> php /root/code/test/crontabTest.php
Content-Type: text/plain; charset=UTF-8
Auto-Submitted: auto-generated
Precedence: bulk
X-Cron-Env: <XDG_SESSION_ID=142>
X-Cron-Env: <XDG_RUNTIME_DIR=/run/user/0>
X-Cron-Env: <LANG=en_US.UTF-8>
X-Cron-Env: <SHELL=/bin/sh>
X-Cron-Env: <HOME=/root>
X-Cron-Env: <PATH=/usr/bin:/bin>
X-Cron-Env: <LOGNAME=root>
X-Cron-Env: <USER=root>
Message-Id: <20191017024615.B67D91202B4@izuf6hxtmn3a1egw9z21hjz.localdomain>
Date: Wed, 16 Oct 2019 18:54:01 +0800 (CST)

/bin/sh: php: command not found

提示我没有找到PHP命令，是PHP的PATH配置有问题，但是复制出来 `php /root/code/test/crontabTest.php param` 可以执行。
于是我改成了绝对路径 `/usr/local/php/bin/php /root/code/test/crontabTest.php param` 然后就没有问题，定时任务按时执行。


