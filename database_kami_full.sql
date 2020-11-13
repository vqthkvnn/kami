create table account
(
    user_name        varchar(30)   not null primary key,
    user_pass        varchar(80)   not null,
    user_status      int           not null default 1,
    user_full_name   nvarchar(255)          default user_name,
    user_birth       date          null,
    user_avatar      varchar(1000) null,
    user_email       varchar(255)  null,
    user_date_create date          not null default NOW(),
    user_date_block  date          null,
    user_linked      int           null,
    user_permission int not null default 0,
    user_note        nvarchar(255) null
);
alter table account
    add unique (user_email);
create table subject
(
    subject_id      int           not null auto_increment primary key,
    subject_content nvarchar(255) not null,
    subject_tag     varchar(255)  not null,
    subject_note    nvarchar(255) null
);
alter table subject
    add unique (subject_tag);
create table notification
(
    notification_id      int         not null auto_increment primary key,
    notification_content TEXT CHARACTER SET utf8,
    notification_date    date        null,
    notification_type    int         null,
    user_name            varchar(30) not null
);
alter table notification
    add foreign key (user_name) references account (user_name);
create table tag
(
    tag_id      int           not null auto_increment primary key,
    tag_content nvarchar(100) not null,
    tag_note    nvarchar(255) null
);
-- post
create table post
(
    post_id          int           not null auto_increment primary key,
    post_date_create date          not null default NOW(),
    user_name        varchar(30)   not null,
    subject_id       int           not null,
    post_status      int           not null default 1,
    post_date_delete date          null,
    post_note        nvarchar(255) null
);
alter table post
    add foreign key (user_name) references account (user_name);
alter table post
    add foreign key (subject_id) references subject (subject_id);
create table post_content
(
    post_id             int         not null,
    post_content_id     int         not null,
    post_content_title nvarchar(255) not null,
    post_content_create date default NOW(),
    user_name           varchar(30) not null,
    post_content_main   TEXT CHARACTER SET utf8
);
alter table post_content
    add primary key (post_id, post_content_id);
alter table post_content
    add foreign key (user_name) references account (user_name);
alter table post_content
    add foreign key (post_id) references post (post_id);
create table post_tag
(
    post_id int not null,
    tag_id  int not null
);
alter table post_tag
    add primary key (post_id, tag_id);
alter table post_tag
    add foreign key (post_id) references post (post_id);
alter table post_tag
    add foreign key (post_id) references tag (tag_id);
create table post_vote
(
    user_name varchar(30) not null,
    post_id   int         not null
);
alter table post_vote
    add primary key (user_name, post_id);
alter table post_vote
    add foreign key (user_name) references account (user_name);
alter table post_vote
    add foreign key (post_id) references post (post_id);
-- comment
create table comment
(
    comment_id            int           not null auto_increment primary key,
    post_id               int           not null,
    comment_date          date          not null default NOW(),
    comment_status        int           not null default 1,
    user_name             varchar(30)   not null,
    comment_quote_post    bit                    default 0,
    comment_quote_comment int           null,
    comment_date_delete   date          null,
    comment_note          nvarchar(255) null
);
alter table comment
    add foreign key (post_id) references post (post_id);
alter table comment
    add foreign key (user_name) references account (user_name);
create table comment_content
(
    comment_id             int not null,
    comment_content_id     int not null,
    comment_content_create date default NOW(),
    comment_content_main   TEXT CHARACTER SET utf8
);
alter table comment_content
    add primary key (comment_id, comment_content_id);
alter table comment_content
    add foreign key (comment_id) references comment (comment_id);
create table comment_vote
(
    user_name  varchar(30) not null,
    comment_id int         not null
);
alter table comment_vote
    add primary key (comment_id, user_name);
alter table comment_vote
    add foreign key (comment_id) references comment (comment_id);
alter table comment_vote
    add foreign key (user_name) references account (user_name);

