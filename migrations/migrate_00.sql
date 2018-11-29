DROP DATABASE IF EXISTS quizportal;

CREATE DATABASE quizportal;
USE quizportal;

CREATE TABLE user
(
  _id INT PRIMARY KEY AUTO_INCREMENT,
  first_name VARCHAR(50) NOT NULL,
  last_name VARCHAR(50) NOT NULL,
  email VARCHAR(50) NOT NULL,
  username VARCHAR(30) NOT NULL,
  password VARCHAR(255) NOT NULL,
  ph_no VARCHAR(10),
  gender ENUM('m', 'f', 'u') DEFAULT 'u',
  join_time TIMESTAMP DEFAULT NOW(),
  avatar VARCHAR(500) DEFAULT 'https://ssl.gstatic.com/images/branding/product/1x/avatar_circle_blue_512dp.png'
);

CREATE TABLE quiz
(
  _id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL UNIQUE,
  start_time TIMESTAMP NOT NULL,
  duration_minutes INT NOT NULL,
  instructor_id INT NOT NULL,
  CONSTRAINT quiz_user_id_fk FOREIGN KEY (instructor_id) REFERENCES user(_id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE question
(
  _id INT PRIMARY KEY AUTO_INCREMENT,
  statement TEXT NOT NULL,
  option_one VARCHAR(400) NOT NULL,
  option_two VARCHAR(400) NOT NULL,
  option_three VARCHAR(400) NOT NULL,
  option_four VARCHAR(400) NOT NULL,
  answer ENUM('A', 'B', 'C', 'D', 'E') NOT NULL,
  quiz_id INT NOT NULL,
  CONSTRAINT question_quiz_id_fk FOREIGN KEY (quiz_id) REFERENCES quiz (_id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE quiz_participant
(
  _id INT PRIMARY KEY AUTO_INCREMENT,
  quiz_id INT NOT NULL,
  user_id INT NOT NULL,
  score DECIMAL(10,2) DEFAULT 0.0,
  CONSTRAINT quiz_fk FOREIGN KEY (quiz_id) REFERENCES quiz(_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT user_id_fk FOREIGN KEY (user_id) REFERENCES user (_id) ON DELETE CASCADE ON UPDATE CASCADE
);

create TABLE quiz_participant_response
(
  _id INT PRIMARY KEY AUTO_INCREMENT,
  quiz_participant_id INT NOT NULL,
  question_number INT NOT NULL,
  response ENUM('A', 'B', 'C', 'D', 'E') NOT NULL,
  CONSTRAINT quiz_participant_fk FOREIGN KEY (quiz_participant_id) REFERENCES quiz_participant(_id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE UNIQUE INDEX user_email_uindex ON user (email);
CREATE UNIQUE INDEX user_username_uindex ON user (username);

CREATE TABLE admin
(
  _id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT NOT NULL,
  CONSTRAINT admin_user_id_fk FOREIGN KEY (user_id) REFERENCES user (_id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE student
(
  _id INT PRIMARY KEY AUTO_INCREMENT,
  roll_number VARCHAR(30) NOT NULL,
  user_id INT NOT NULL,
  CONSTRAINT student_user_id_fk FOREIGN KEY (user_id) REFERENCES user (_id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE instructor
(
  _id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT NOT NULL,
  CONSTRAINT instructor_user_id_fk FOREIGN KEY (user_id) REFERENCES user (_id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE instructor_quiz
(
  instructor_id INT NOT NULL,
  quiz_id INT NOT NULL,
  CONSTRAINT instructors_quiz_pk PRIMARY KEY (instructor_id, quiz_id),
  CONSTRAINT instructors_quiz_user_id_fk FOREIGN KEY (instructor_id) REFERENCES instructor(_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT instructors_quiz_id_fk FOREIGN KEY (quiz_id) REFERENCES quiz(_id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE student_quiz
(
  student_id INT NOT NULL,
  quiz_id INT NOT NULL,
  CONSTRAINT students_quiz_pk PRIMARY KEY (student_id, quiz_id),
  CONSTRAINT students_quiz_user_id_fk FOREIGN KEY (student_id) REFERENCES student(_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT students_quiz_id_fk FOREIGN KEY (quiz_id) REFERENCES quiz(_id) ON DELETE CASCADE ON UPDATE CASCADE
);
