-- Dropping Tables
DROP TABLE IF EXISTS e_Assign;
DROP TABLE IF EXISTS e_Signup;
DROP TABLE IF EXISTS e_Certs;
DROP TABLE IF EXISTS e_Event;
DROP TABLE IF EXISTS e_Info;
DROP TABLE IF EXISTS e_Member;
DROP TABLE IF EXISTS e_User;


-- Creating Tables
CREATE TABLE e_User (
    uid INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(25),
    password VARCHAR(25),
    siteRole VARCHAR(10)
) ENGINE = innodb;


CREATE TABLE e_Member (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstName VARCHAR(20) NOT NULL,
    lastName VARCHAR(30) NOT NULL,
    points INT,
    uid INT NOT NULL,
    FOREIGN KEY (uid) REFERENCES e_User(uid)
) ENGINE = innodb;


-- This table is used to prevent the bloating of the member table if this system is ever officialized.
CREATE TABLE e_Info (
    member_id INT NOT NULL,
    dateSignedUp DATE,
    FOREIGN KEY (member_id) REFERENCES e_Member(id)
) ENGINE = innodb;


CREATE TABLE e_Event (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(50),
    dateTimeStart DATETIME,
    dateTimeEnd DATETIME,
    location VARCHAR(100),
    details TEXT
) ENGINE = innodb;


CREATE TABLE e_Certs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50),
    idenNumber INT
) ENGINE = innodb;


CREATE TABLE e_Cert_Assign (
    cert_id INT NOT NULL,
    member_id INT NOT NULL,
    startDate DATE,
    expireDate DATE,
    FOREIGN KEY (cert_id) REFERENCES e_Certs(id),
    FOREIGN KEY (member_id) REFERENCES e_Member(id)
) ENGINE = innodb;


CREATE TABLE e_Signup (
member_id INT NOT NULL,
event_id INT NOT NULL,
memberRole VARCHAR(20),
FOREIGN KEY (member_id) REFERENCES e_Member(id),
FOREIGN KEY (event_id) REFERENCES e_Event(id)
) ENGINE = innodb;