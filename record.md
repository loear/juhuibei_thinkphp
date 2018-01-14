## 操作记录

CREATE DATABASE `ng_intivation` charset=utf8;

GRANT ALL PRIVILEGES ON `ng_intivation`.* TO 'guo'@'%' IDENTIFIED BY 'sn_123456' WITH GRANT OPTION;

FLUSH PRIVILEGES;

## 错误代码

| Code  | 描述  |
| :------------ | :------------ |
| 00000  | 成功  |
| 10000  | 新增数据失败  |
| 10001  | 文件（夹）已存在，一般用于创建文件夹失败提示 |
| 10002  | 删除/重命名文件（夹）失败，一般是权限问题  |
| 10003  | 非法删除，原因是文件夹下有子文件 |
| 10004  | 无效的主键，主键值<=0 |
| 10005  | 提交的数据不合法，或参数有误 |
| 10006  | 无法获取上传的文件 |
| 10007  | 该不能被修改，id错误或是不可被修改的目录 |
| 10008  | 错误的请求方式 |  


TRUNCATE ng_card;
TRUNCATE ng_config;
TRUNCATE ng_module;
TRUNCATE ng_module_tag;
TRUNCATE ng_music;
TRUNCATE ng_tag;
TRUNCATE ng_template;
TRUNCATE ng_theme;
TRUNCATE ng_theme_module;

37

73

https://www.pica.im/card/preview/26#pc_preview