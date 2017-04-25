DROP TABLE marks_logs

GO


DROP TABLE requests

GO


DROP TABLE tasks_disciplines

GO


DROP TABLE disciplines

GO


DROP TABLE tasks

GO


DROP TABLE students

GO


DROP TABLE groups

GO


DROP TABLE professors

GO


DROP TABLE users

GO
CREATE TABLE users
(
	id int identity primary key,
	email varchar(255) NOT NULL unique,
	name varchar(255) NOT NULL,
	surname varchar(255) NOT NULL,
	middlename varchar(255),
	password varchar(255) NOT NULL,
	remember_token varchar(100),
	created_at DATETIME NOT NULL
)

GO

CREATE TABLE professors
(
	id int identity primary key,
	user_id int NOT NULL references users(id) unique,
	occupation varchar(255),
	degree varchar(255)
)

GO

CREATE TABLE groups
(
	id int identity primary key,
	name varchar(255) NOT NULL unique
)

GO

CREATE TABLE students
(
	id int identity primary key,
	user_id int NOT NULL references users(id) unique,
	group_id int NOT NULL references groups(id)
)

GO

CREATE TABLE tasks
(
	id int identity primary key,
	professor_id int NOT NULL references professors(id),
	type int NOT NULL,
	title varchar(255) NOT NULL,
	description varchar(255) NOT NULL,
	technologies varchar(255),
	created_at datetime NOT NULL,
	updated_at datetime
)

GO

CREATE TABLE disciplines
(
	id int identity primary key,
	name varchar(255) NOT NULL unique
)

GO

CREATE TABLE tasks_disciplines
(
	task_id int NOT NULL references tasks(id),
	discipline_id int NOT NULL references disciplines(id),
	PRIMARY KEY(task_id, discipline_id)
)

GO

CREATE TABLE requests
(
	task_id int NOT NULL references tasks(id),
	student_id int NOT NULL references students(id),
	status int NOT NULL,
	message varchar(255),
	mark int,
	created_at datetime NOT NULL,
	started_at datetime,
	PRIMARY KEY(task_id, student_id)
)

GO

CREATE TABLE marks_logs
(
	id int identity primary key,
	task_id int,
	old_value int,
	new_value int,
	updated_at datetime
)

GO


CREATE TRIGGER UpdateMark ON requests AFTER UPDATE
AS
	IF UPDATE(mark)
	BEGIN
		declare
			@task_id int,
			@old_val varchar(255),
			@new_val varchar(255)
			select @old_val = mark from deleted
			select @new_val = mark from inserted
			select @task_id = task_id from inserted

		INSERT INTO marks_logs(task_id, old_value, new_value, updated_at)
			VALUES(@task_id, @old_val, @new_val, GETDATE() )
	END
GO
