**PHP采集器pickupCatalog.php & pickupGoods.php**


**使用方式：CLI**
- 采集:# php -f pickupGoods start end


**命令行参数：** 

|参数名|必选|类型|说明|
|:----    |:---|:----- |-----   |
|pickupFilename|是  |string |执行文件名 |
|start|否  |int |起始计数 默认值: 0 |
|end|否  |int |结束计数 |

**后台执行**

``` 

# nohup php -f pickupGoods.php 1 1000 >result.txt 2>&1 &

```


**导出Excel exportExcel.php**

**使用方式：CLI**
- 采集:# php -f exportExcel.php

**后台执行**

``` 

# php -f exportExcel.php

```

**发送Email sendMail.php**

**使用方式：CLI**
- 采集:# php -f sendMail.php


**定时任务执行**

``` 

0 2 * * * php -f /webroot/pickup/sendMail.php  >/dev/null 2>&1 &

```


**图片本地化 imglocally.php**

**使用方式：CLI**
- 采集:# php -f imglocally.php


**后台执行**

``` 

# nohup php -f imglocally.php 1 5000 >result.txt 2>&1 &

```

**命令行下查看图片 ascImage.php**

**使用方式：CLI**
- # php -f ascImage.php num
**命令行参数：** 

|参数名|必选|类型|说明|
|:----    |:---|:----- |-----   |
|num|否  |int |清晰度1:低清  2：中等  3：高等  |


**CLI执行**

``` 

# php ascImage.php imagefilename.jpg 3

```