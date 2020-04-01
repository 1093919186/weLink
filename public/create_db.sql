/* 首页基础信息查询  修改头像 轮播   好友请求 非本人好友的申请好友  主页表单提交修改，信息查询   余星星3人*/
/* 登录注册  搜索  我的相册 发布动态  搜索人 周所长2人*/
/*信息表由星星组自己建立*/
create database  welink  character set utf8;

use welink;

create table  user
(
   userid  int  primary key  auto_increment,
   name  varchar(50)  unique not null,
   username  varchar(100)  not null  unique,
   password  varchar(100)  not null,
   iconpath  varchar(100)  default 'public/images/默认头像.png',
   school varchar(100),
   email  varchar(100),
   gexing  varchar(100),
   dateandtime timestamp  default current_timestamp,
   power  int default 1,
   jftime BIGINT default 0
);

insert into user(name,username,password,email,school,gexing)values('马西骑','1093919186','123456','1093919186@qq.com','中南大学','爱是一道光');
insert into user(name,username,password,email,school,gexing)values('周所长','123456','123456','123456@qq.com','长沙达内','我是周所长');
insert into user(name,username,password,email,school,gexing)values('余星星','654321','123456','654321@qq.com','长沙达内','我是星星');

create table circle
(
  circleid        int        auto_increment        primary key,
  fbman    varchar(50)  not null,
  content  varchar(1000)  not null,
  numzan int default 0,
  numpl int default 0,
  dateandtime    timestamp    default current_timestamp
);

insert into circle(fbman,content,numpl)values('马西骑','今天给孟子义表白了，哈哈她犹豫了一下，说明我还有机会。继续努力，加油马西骑。','1');
insert into circle(fbman,content)values('马西骑','今天给孟子义表白了。');

create table reviews
(
  reviewid        int        auto_increment        primary key,
  circleid    int,
  foreign key (circleid) references circle(circleid),
  plman    varchar(50)  not null,
  iconpath  varchar(100)  default 'public/images/默认头像.png',
  content  varchar(1000)  not null,
  dateandtime    timestamp    default current_timestamp
);

insert into reviews(circleid,plman,content)values('1','周所长','祝你幸福，爱你么么哒');
insert into reviews(circleid,plman,content)values('1','周所长','祝你幸福，爱你');

create table circleimage
(
  imageid        int        auto_increment        primary key,
  circleid    int,
  foreign key (circleid) references circle(circleid),
  picpath varchar(100) unique,
  dateandtime    timestamp    default current_timestamp
);

insert into circleimage(circleid,picpath)values('1','public/images/朋友圈/朋友圈1.jpeg');
insert into circleimage(circleid,picpath)values('1','public/images/朋友圈/朋友圈2.jpeg');
insert into circleimage(circleid,picpath)values('1','public/images/朋友圈/朋友圈3.jpg');

create table image
(
  picid int primary key auto_increment,
  picpath varchar(100) unique,
  userid int
);

insert into image(picpath,userid)values('public/images/相册/picture1.png','1');
insert into image(picpath,userid)values('public/images/相册/picture2.png','1');
insert into image(picpath,userid)values('public/images/相册/picture3.png','1');
insert into image(picpath,userid)values('public/images/相册/picture4.png','1');
insert into image(picpath,userid)values('public/images/相册/picture5.png','1');


create table link
(
   linkid int primary key auto_increment,
   userid int,
   friendid int
);

insert into link(userid,friendid)values('1','2');
insert into link(userid,friendid)values('2','1');