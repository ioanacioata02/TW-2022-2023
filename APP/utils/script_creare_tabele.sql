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
	tests JSONB NOT NULL,
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
	password BYTEA NOT NULL,
	nr_attempts INT NOT NULL,
	nr_successes INT NOT NULL
);

CREATE TABLE proposed_problems(
	id SERIAL PRIMARY KEY,
	name TEXT NOT NULL,
	description TEXT NOT NULL,
	tags TEXT[] NOT NULL,
	tests JSONB NOT NULL,
	id_author INT NOT NULL,
	CONSTRAINT fk_author_user FOREIGN KEY (id_author) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE solutions(
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
	CONSTRAINT fk_member_user FOREIGN KEY (id_user) REFERENCES users(id) ON DELETE CASCADE,
	CONSTRAINT fk_class_id FOREIGN KEY (id_class) REFERENCES classes(id) ON DELETE CASCADE
);

CREATE TABLE homeworks(
	id SERIAL PRIMARY KEY,
	topic TEXT NOT NULL,
	deadline DATE NOT NULL
);

CREATE TABLE homework_problems(
	id_homework INT NOT NULL,
	id_problem_hw INT NOT NULL,
	CONSTRAINT fk_homework FOREIGN KEY (id_homework) REFERENCES homeworks(id) ON DELETE CASCADE,
	CONSTRAINT fk_problem FOREIGN KEY (id_problem_hw) REFERENCES problems(id) ON DELETE CASCADE
);
CREATE TABLE homework_members(
	id_homework_mb INT NOT NULL,
	id_member INT NOT NULL,
	solved_nr INT NOT NULL,
	problem_nr INT NOT NULL,
	CONSTRAINT fk_homework2 FOREIGN KEY (id_homework_mb) REFERENCES homeworks(id) ON DELETE CASCADE,
	CONSTRAINT fk_member FOREIGN KEY (id_member) REFERENCES users(id) ON DELETE CASCADE
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
	INSERT INTO problems (name, description, tags, tests, nr_attempts, nr_successes)
		SELECT name, description, tags, tests, 0, 0 FROM proposed_problems
    	WHERE id = id_proposed;
	
	DELETE FROM proposed_problems WHERE id = id_proposed;
END;$$;
select usename from pg_user;
SELECT current_database();