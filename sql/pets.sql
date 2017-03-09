drop table if exists pets;

create table pets (
	name varchar(50),
	species varchar(50),
	age int);

insert into pets (name, species, age) values ('skeeter', 'dog', 5);
insert into pets (name, species, age) values ('fido', 'dog', 10);
insert into pets (name, species, age) values ('itchy', 'dog', 3);
insert into pets (name, species, age) values ('fluffy', 'cat', 1);
insert into pets (name, species, age) values ('scratchy', 'cat', 5);
insert into pets (name, species, age) values ('goldie', 'fish', 1);
insert into pets (name, species, age) values ('lizzie', 'iguana', 25);
