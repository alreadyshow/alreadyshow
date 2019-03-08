id:每个select子句的标识id
select_type:select语句的类型
table:当前表名
显示查询将访问的分区，如果你的查询是基于分区表
type：当前表内访问方式
possible_keys:可能使用到的索引
key:经过优化器评估最终使用的索引
key_length:使用到的索引长度
ref:引用到的上一个表的列
rows:rows_examined，要得到最终记录索要扫描经过的记录数
filtered:表示存储引擎返回的数据在server层过滤后，剩下多少满足查询的记录数量的比例，注意是百分比，不是具体记录数。
Extra:额外的信息说明


-- 查看MySQL是否开启event
show variables like 'event_scheduler';