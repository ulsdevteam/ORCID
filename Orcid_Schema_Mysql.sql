create database ULS;

create table ULS.orcid_users
   (	id bigint not null auto_increment,
	username varchar(8) not null,
	orcid varchar(19),
	token varchar(255),
	created date,
	modified date,
	primary key (id)
   );

create table ULS.orcid_status_types
   (	id bigint not null auto_increment,
	name varchar(512) not null,
	seq numeric(38,0),
	primary key (id)
   );

create table ULS.orcid_batch_creators
   (	id bigint not null auto_increment,
	name varchar(8) not null,
	flags bigint default 0 not null,
	primary key (id)
   );

create table ULS.orcid_batch_groups
   (	id bigint not null auto_increment,
	name varchar(512) not null,
	group_definition varchar(2048),
	employee_definition varchar(2048),
	student_definition varchar(2048),
	cache_creation_date date,
	primary key (id)
   );

create table ULS.orcid_batches
   (	id bigint not null auto_increment,
	name varchar(512) not null,
	subject varchar(512) not null,
	body longtext not null,
	from_name varchar(64) not null,
	from_addr varchar(64) not null,
	reply_to varchar(64),
	orcid_batch_creator_id bigint not null,
	primary key (id),
	foreign key (orcid_batch_creator_id) references orcid_batch_creators(id)
   );

create table ULS.orcid_batch_group_caches
   (	id bigint not null auto_increment,
	orcid_batch_group_id bigint not null,
	orcid_user_id bigint not null,
	deprecated date,
	primary key (id),
	foreign key (orcid_batch_group_id) references orcid_batch_groups(id),
	foreign key (orcid_user_id) references orcid_users(id)
   );

create table ULS.orcid_batch_triggers
   (	id bigint not null auto_increment,
	name varchar(512) not null,
	orcid_status_type_id bigint not null,
	orcid_batch_id bigint not null,
	trigger_delay numeric(38,0) not null,
	orcid_batch_group_id bigint,
	begin_date date,
	repeat_value numeric(38,0) default 0 not null,
	maximum_repeat numeric(38,0) default 0 not null,
	primary key (id),
	foreign key (orcid_batch_id) references orcid_batches(id),
	foreign key (orcid_status_type_id) references orcid_status_types(id),
	foreign key (orcid_batch_group_id) references orcid_batch_groups(id)
   );

create table ULS.orcid_emails
   (	id bigint not null auto_increment,
	orcid_user_id bigint not null,
	orcid_batch_id bigint not null,
	queued date,
	sent date,
	cancelled date,
	primary key (id),
	foreign key (orcid_user_id) references orcid_users(id),
	foreign key (orcid_batch_id) references orcid_batches(id)
   );

create table ULS.orcid_statuses
   (	id bigint not null auto_increment,
	orcid_user_id bigint not null,
	orcid_status_type_id bigint not null,
	status_timestamp date not null,
	primary key (id),
	foreign key (orcid_user_id) references orcid_users(id),
	foreign key (orcid_status_type_id) references orcid_status_types(id)
   );

CREATE VIEW AS all_orcid_statuses 
select 
  orcid_statuses.orcid_user_id, 
  orcid_statuses.orcid_status_type_id, 
  orcid_statuses.status_timestamp 
from 
  orcid_statuses 
union 
select 
  orcid_users.id orcid_user_id, 
  orcid_status_types.id orcid_status_type_id, 
  orcid_users.created status_timestamp 
from 
  orcid_users 
  join orcid_status_types on (orcid_status_types.seq = 0) 
union 
select 
  orcid_emails.orcid_user_id, 
  orcid_status_types.id orcid_status_type_id, 
  min(orcid_emails.sent) status_timestamp 
from 
  orcid_emails 
  join orcid_status_types on (orcid_status_types.seq = 1) 
where 
  sent is not null 
group by 
  orcid_emails.orcid_user_id, 
  orcid_status_types.id 
union 
select 
  orcid_users.id orcid_user_id, 
  orcid_status_types.id orcid_status_type_id, 
  orcid_users.modified status_timestamp 
from 
  orcid_users 
  join orcid_status_types on (
    orcid_users.orcid is not null 
    and orcid_status_types.seq = 3
  ) 
union 
select 
  orcid_users.id orcid_user_id, 
  orcid_status_types.id orcid_status_type_id, 
  orcid_users.modified status_timestamp 
from 
  orcid_users 
  join orcid_status_types on (
    orcid_users.orcid is not null 
    and orcid_users.token is not null 
    and orcid_status_types.seq = 4
  )


create view current_orcid_status as(
  select 
    all_orcid_statuses.orcid_user_id, 
    all_orcid_statuses.orcid_status_type_id, 
    all_orcid_statuses.status_timestamp 
  from 
    all_orcid_statuses 
    join orcid_status_types on (
      all_orcid_statuses.orcid_status_type_id = orcid_status_types.id
    ) 
  where 
    orcid_user_id not in (
      select 
        s.orcid_user_id 
      from 
        all_orcid_statuses s 
        join orcid_status_types t on (s.orcid_status_type_id = t.id) 
      where 
        s.orcid_user_id = all_orcid_statuses.orcid_user_id 
        and t.seq > orcid_status_types.seq
    )
)
