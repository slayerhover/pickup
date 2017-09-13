**功能描述**
- PHP采集器


**使用方式：CLI**
- 采集:# php -f pickupFilename start end


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


**功能描述**
- 导出Excel

**使用方式：CLI**
- 采集:# php -f exportExcel.php

**功能描述**
- 发送Email

**使用方式：CLI**
- 采集:# php -f sendMail.php


**定时任务执行**

``` 

0 2 * * * php -f /webroot/pickup/sendMail.php  >/dev/null 2>&1 &

```
