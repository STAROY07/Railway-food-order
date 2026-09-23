CREATE DATABASE railway_food_adv;
USE railway_food_adv;

CREATE TABLE users(id int auto_increment primary key,name varchar(50),email varchar(50),password varchar(50));
CREATE TABLE orders(id int auto_increment primary key,user varchar(50),pnr varchar(20),food varchar(50),category varchar(10),coach varchar(10),seat varchar(10),station varchar(50),payment varchar(20),status varchar(20));
CREATE TABLE feedback(id int auto_increment primary key,user varchar(50),rating int,message text);
