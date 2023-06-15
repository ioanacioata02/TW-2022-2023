-- Database: InformatiX

-- DROP DATABASE IF EXISTS "InformatiX";

CREATE DATABASE "InformatiX"
    WITH
    OWNER = postgres
    ENCODING = 'UTF8'
    LC_COLLATE = 'English_United States.1252'
    LC_CTYPE = 'English_United States.1252'
    TABLESPACE = pg_default
    CONNECTION LIMIT = -1
    IS_TEMPLATE = False;
	
	
DROP TABLE IF EXISTS problems CASCADE;
DROP TABLE IF EXISTS proposed_problems CASCADE;
DROP TABLE IF EXISTS users CASCADE;
DROP TABLE IF EXISTS solutions CASCADE;
DROP TABLE IF EXISTS classes CASCADE;
DROP TABLE IF EXISTS class_members CASCADE;
DROP TABLE IF EXISTS homeworks CASCADE;
DROP TABLE IF EXISTS homework_problems CASCADE;
DROP TABLE IF EXISTS homework_members CASCADE;
DROP TABLE IF EXISTS all_comments CASCADE;

CREATE TABLE problems(
	id SERIAL PRIMARY KEY,
	name TEXT NOT NULL,
	description TEXT NOT NULL,
	tags TEXT[] NOT NULL,
	nr_attempts INT NOT NULL,
	nr_successes INT NOT NULL
);

CREATE TABLE users(
	id SERIAL PRIMARY KEY,
	status INT NOT NULL,
	img TEXT,
	first_name TEXT NOT NULL,
	last_name TEXT NOT NULL,
	username TEXT NOT NULL,
	email TEXT NOT NULL UNIQUE,
	password TEXT NOT NULL,
	nr_attempts INT NOT NULL,
	nr_successes INT NOT NULL
);

CREATE TABLE proposed_problems(
	id SERIAL PRIMARY KEY,
	name TEXT NOT NULL,
	description TEXT NOT NULL,
	tags TEXT[] NOT NULL,
	id_author INT NOT NULL,
	CONSTRAINT fk_author_user FOREIGN KEY (id_author) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE solutions(
	id SERIAL PRIMARY KEY,
	id_user INT NOT NULL,
	id_problem INT NOT NULL,
	solution TEXT NOT NULL,
	success BOOLEAN NOT NULL,
	moment TIMESTAMP DEFAULT NOW(),
	CONSTRAINT fk_solved_user FOREIGN KEY (id_user) REFERENCES users(id) ON DELETE CASCADE,
	CONSTRAINT fk_solved_pb FOREIGN KEY (id_problem) REFERENCES problems(id) ON DELETE CASCADE
);

CREATE TABLE classes(
	id SERIAL PRIMARY KEY,
	name TEXT NOT NULL
);

CREATE TABLE class_members(
	id_class INT NOT NULL,
	id_user INT NOT NULL,
	CONSTRAINT pk_class_members PRIMARY KEY (id_class, id_user),
	CONSTRAINT fk_member_user FOREIGN KEY (id_user) REFERENCES users(id) ON DELETE CASCADE,
	CONSTRAINT fk_class_id FOREIGN KEY (id_class) REFERENCES classes(id) ON DELETE CASCADE
);

create table homeworks(
    id serial PRIMARY KEY,
    topic TEXT not NULL,
    author INT not null,
    deadline int NOT NULL,
    problems_id INT[] NOT NULL
);


create table homework_members(
    id_homework INT not NULL,
    id_student INT NOT NULL,
    problems JSONB NOT NULL,
	deadline int NOT NULL,
    CONSTRAINT fk_homework FOREIGN KEY (id_homework) REFERENCES homeworks (id),
    CONSTRAINT fk_student FOREIGN KEY (id_student) REFERENCES students (id),
    CONSTRAINT pk_homework_member PRIMARY KEY (id_homework, id_student)

);


CREATE TABLE all_comments(
	id_user INT NOT NULL,
	id_problem INT NOT NULL,
	comment_txt TEXT NOT NULL,
	moment TIMESTAMP DEFAULT NOW(),
	CONSTRAINT fk_solved_user FOREIGN KEY (id_user) REFERENCES users(id) ON DELETE CASCADE,
	CONSTRAINT fk_solved_pb FOREIGN KEY (id_problem) REFERENCES problems(id) ON DELETE CASCADE
);



CREATE OR REPLACE PROCEDURE accept_probl(id_proposed INT)
LANGUAGE PLPGSQL
AS $$
BEGIN
	INSERT INTO problems (name, description, tags, nr_attempts, nr_successes)
		SELECT name, description, tags, 0, 0 FROM proposed_problems
    	WHERE id = id_proposed;
	
	DELETE FROM proposed_problems WHERE id = id_proposed;
END;$$;
select usename from pg_user;
SELECT current_database();
