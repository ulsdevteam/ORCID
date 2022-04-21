create database ULS;

create table ULS.orcid_users
   (	id bigint not null, 
	username varchar(8) not null, 
	orcid varchar(19), 
	token varchar(255), 
	created date, 
	modified date,
	primary key (id)
   );
   
create table ULS.orcid_status_types
   (	id bigint not null, 
	name varchar(512) not null, 
	seq numeric(38,0),
	primary key (id)
   );
   
create table ULS.orcid_batch_creators
   (	id bigint not null, 
	name varchar(8) not null, 
	flags bigint default 0 not null,
	primary key (id)
   );
   
create table ULS.orcid_batch_groups
   (	id bigint not null, 
	name varchar(512) not null, 
	group_definition varchar(2048), 
	employee_definition varchar(2048), 
	student_definition varchar(2048), 
	cache_creation_date date,
	primary key (id)
   );
   
create table ULS.orcid_batches
   (	id bigint not null, 
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
   (	id bigint not null, 
	orcid_batch_group_id bigint not null, 
	orcid_user_id bigint not null, 
	deprecated date,
	primary key (id),
	foreign key (orcid_batch_group_id) references orcid_batch_groups(id),
	foreign key (orcid_user_id) references orcid_users(id)
   );

create table ULS.orcid_batch_triggers
   (	id bigint not null, 
	name varchar(512) not null, 
	orcid_status_type_id bigint not null, 
	orcid_batch_id bigint not null, 
	trigger_delay numeric(38,0) not null, 
	orcid_batch_group_id bigint, 
	begin_date date, 
	`repeat` numeric(38,0) default 0 not null, 
	maximum_repeat numeric(38,0) default 0 not null,
	primary key (id),
	foreign key (orcid_batch_id) references orcid_batches(id),
	foreign key (orcid_status_type_id) references orcid_status_types(id),
	foreign key (orcid_batch_group_id) references orcid_batch_groups(id)
   );

create table ULS.orcid_emails
   (	id bigint not null, 
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
   (	id bigint not null, 
	orcid_user_id bigint not null, 
	orcid_status_type_id bigint not null, 
	status_timestamp date not null,
	primary key (id),
	foreign key (orcid_user_id) references orcid_users(id),
	foreign key (orcid_status_type_id) references orcid_status_types(id)
   );
