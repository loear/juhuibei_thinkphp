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

card.js 74行

```angular2html
          e.cover += "?/sq/150", p.ready(function() {
```

去掉`?/sq/150`

```angular2html
          e.cover += "", p.ready(function() {
```

236行

```angular2html
 p && (h.value = 1e3 * d), u.indexOf("lsc_time") > -1 && (h.value = this.data.wedding_time ? 1e3 * this.data.wedding_time : +new Date), u.indexOf("phone") > -1 && l.push("phone"), this.webp_usable && /jpg|png|jpeg$/.test(d) && (h.value = d + "!webp"), n || p || (h.font_family && (this.fonts_url.push(h.font_family), this.font_family_list[o] || (this.font_family_list[o] = []), E && _.set(t.module_id + u + "_font", h.font_family), this.font_family_list[o].push({
```

去掉`!webp`

```angular2html
 p && (h.value = 1e3 * d), u.indexOf("lsc_time") > -1 && (h.value = this.data.wedding_time ? 1e3 * this.data.wedding_time : +new Date), u.indexOf("phone") > -1 && l.push("phone"), this.webp_usable && /jpg|png|jpeg$/.test(d) && (h.value = d + ""), n || p || (h.font_family && (this.fonts_url.push(h.font_family), this.font_family_list[o] || (this.font_family_list[o] = []), E && _.set(t.module_id + u + "_font", h.font_family), this.font_family_list[o].push({
```

174 行

```angular2html
 i.config = t, $("#loading-cover").css("background-image", "url(" + t.cover + "!/sq/150)"), t.global_css && (new m(null, null, t.global_css).renderCss("global"), this.renderCustomFrames(t.global_css)), d.init(), d.start();
```

去掉去掉`!/sq/150`

```angular2html
 i.config = t, $("#loading-cover").css("background-image", "url(" + t.cover + ")"), t.global_css && (new m(null, null, t.global_css).renderCss("global"), this.renderCustomFrames(t.global_css)), d.init(), d.start();
```


```angular2html
1. 用户有依次为普通、黄金、钻石、至尊4个等级;
2. 黄金等级以上的用户所创建的聚会活动可永久保存;
3. 普通用户创建的在聚会结束后一段时间会空间释放;
4. 黄金及以上的用户上传照片至「影集」不限制数量;
5. 普通用户每个聚会都可以上传3张照片至「影集」;
6. 普通用户在「个人中心」可以升级为黄金用户。
```

```angular2html
1. 用户有依次为普通、黄金、钻石、至尊4个等级;
2. 只有黄金等级以上的用户可以制作婚礼请柬;
3. 钻石等级以上用户创建请柬不限数量;
4. 至尊用户享受定制的婚礼请柬;
```