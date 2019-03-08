-- git 命令
-- git archive -o latest.zip NEW_COMMIT_ID_HERE $(git diff --name-only OLD_COMMIT_ID_HERE NEW_COMMIT_ID_HERE)

-- git archive --format=zip HEAD `git diff --name-only 新的commit 旧的commit` > a.zip

-- git archive --format=zip HEAD `git diff --name-only f96ce6f 2c61a0a` > a.zip

-- git archive -o latest.zip f96ce6f $(git diff --name-only 2c61a0a f96ce6f)

-- git diff 2c61a0a f96ce6f --name-only | xargs tar -czvf ./update.tar.gz

-- git diff 2c61a0a f96ce6f --name-only | xargs -t -i{} cp --parents {} ./update


-- 查看官方/客服账号
select * from gm_user where id in (select user_id from gm_auth_assignment where item_name = '客服');
select * from gm_user where id in (select user_id from gm_auth_assignment where item_name = '官方权限');

-- 查看俱乐部积分差异值
SELECT a.*,b.caption_deduction,a.caption_deduction-b.caption_deduction diff from
(select club_id,captain_id,sum(count_caption_deduction) caption_deduction from t_w_player_club_statistic where DATE(create_time) = '20190121' and captain_id > 0 and count_caption_deduction > 0 GROUP BY club_id,captain_id) a LEFT JOIN
(select club_id,team_id captain_id,sum(middle_coin) caption_deduction from t_u_player_club_coin_log where DATE(create_time) = '20190121' and op_type = 3 and description > 3 GROUP BY club_id,team_id) b
on a.club_id = b.club_id and a.captain_id = b.captain_id;

-- 查看俱乐部积分差异值
select cc.club_id,cc.team_id,case when cc.dedution_rate is null then 0 else sum(cc.deduction*(cc.dedution_rate/100)) end as diff from
	(select a.*,b.dedution_rate from t_u_room_card_coin a LEFT JOIN (
		select club_id,captain_id,dedution_rate from t_u_player_club where dedution_rate != 0
	) b on a.club_id=b.club_id and a.team_id=b.captain_id where a.team_id !=0 and a.end_time >= '20190122' and a.end_time < '20190123' and a.dedution_rate_temp = 0) cc
GROUP BY cc.club_id,cc.team_id ORDER BY 3 desc


-- 查询总库大小
select (sum(DATA_LENGTH)+sum(INDEX_LENGTH))/1024/1024 from information_schema.`TABLES` where table_schema = 'bjyl' order by table_rows desc;
-- 查询每个表大小
select table_name,concat(round(sum(DATA_LENGTH/1024/1024),2),'M') size from information_schema.tables where table_schema='bjyl' GROUP BY table_name ORDER BY 2 desc; 

select CURDATE() create_time,TABLE_NAME table_name,TABLE_ROWS table_rows, CAST(data_length/1024/1024 AS SIGNED INTEGER) data_size,CAST(index_length/1024/1024 AS SIGNED INTEGER) 
from information_schema.tables where TABLE_SCHEMA = 'jindawang' 
group by TABLE_NAME order by 3 desc;

-- 查看特权操作记录
select * from gm_sys_log where message like '%特权%' and logtime >= DATE_ADD(CURDATE(),INTERVAL -14 day);


-- 查看收益 按代理等级划分
select b.lv,sum(a.deduction) from t_b_deduction_log a LEFT JOIN t_b_agent b on a.accept_id = b.id where a.log_date >= DATE_ADD(CURDATE(),INTERVAL -30 day) and log_date < CURDATE() GROUP BY b.lv;

-- 查看玩家充值
select sum(price) from t_u_deposit where purchase_time >= DATE_ADD(CURDATE(),INTERVAL -30 day) and is_exist = 0;

-- 查看代理收益充值 
select sum(price) from t_u_behalf_recharge_log where recharge_type = 1 and create_time >=  DATE_ADD(CURDATE(),INTERVAL -30 day) and create_time < CURDATE(); -- 85130


-- 查询连续数据
root@127.0.0.1 : test > select * from itpub1; 
+------+------+
| id | name |
+------+------+
| 1 | 1 |
| 2 | 1 |
| 3 | 1 |
| 4 | 1 |
| 5 | 2 |
| 6 | 2 |
| 7 | 1 |
| 8 | 1 |
+------+------+
8 rows in set (0.00 sec)
root@127.0.0.1 : test > select aa.name,max(rownum) maxrow from ( select ib.id,ib.name,if((ib.name=@pname) , @row:=@row+1 , @row:=1) rownum , @pname:=ib.name pn from itpub1 ib, (select @row:=0, @pname:='000000') row1 ) aa group by aa.name;
+------+--------+
| name | maxrow |
+------+--------+
| 1 | 4 |
| 2 | 2 |
+------+--------+
2 rows in set (0.00 sec)
