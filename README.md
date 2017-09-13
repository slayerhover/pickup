**功能描述**
- PHP采集器


**使用方式：**
- CLI:# php -f pickupGoods.php start end


**命令行参数：** 

|参数名|必选|类型|说明|
|:----    |:---|:----- |-----   |
|start|否  |int |起始计数 默认值: 0 |
|end|否  |int |结束计数 |

**后台执行**

``` 

# nohup php -f pickupGoods.php 1 1000 >result.txt 2>&1 &

```
