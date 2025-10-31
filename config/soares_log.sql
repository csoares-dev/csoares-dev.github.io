
create database soares_log;
use soares_log;

/*Tabela para armazenar informações dos usuários*/
create table usuarios(
id int auto_increment primary key,
nome varchar(100) not null unique,
email varchar(100) unique,
sexo char(1),
Data_nasc date not null,
senha varchar (255),
cidade varchar(255) not null,
estado char(2),
telefone varchar(20)
);
select * from usuarios;
drop table usuarios;