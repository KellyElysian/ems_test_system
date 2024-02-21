-- Dropping Tables (DO NOT UNCOMMENT UNLESS NEEDED)
-- DROP TABLE IF EXISTS e_Anno_Creator;
-- DROP TABLE IF EXISTS e_Assign;
-- DROP TABLE IF EXISTS e_Signup;
-- DROP TABLE IF EXISTS e_Cert;
-- DROP TABLE IF EXISTS e_Event;
-- DROP TABLE IF EXISTS e_Info;
-- DROP TABLE IF EXISTS e_Member;
-- DROP TABLE IF EXISTS e_User;
-- DROP TABLE IF EXISTS e_Announcement;


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
    status BOOLEAN,
    uid INT NOT NULL,
    FOREIGN KEY (uid) REFERENCES e_User(uid)
) ENGINE = innodb;


-- This table is used to prevent the bloating of the member table if this system is ever officialized.
CREATE TABLE e_Info (
    member_id INT NOT NULL,
    dateSignedUp DATE,
    notes TEXT,
    FOREIGN KEY (member_id) REFERENCES e_Member(id)
) ENGINE = innodb;


CREATE TABLE e_Member_Edit (
    editor_id INT,
    member_edited INT,
    editTime DATETIME,
    FOREIGN KEY (editor_id) REFERENCES e_Member(id),
    FOREIGN KEY (member_edited) REFERENCES e_Member(id)
) ENGINE = innodb;


CREATE TABLE e_Event (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title TEXT, 
    points INT DEFAULT 100,
    dateTimeStart DATETIME,
    dateTimeEnd DATETIME,
    location VARCHAR(100),
    closed INT DEFAULT 0,
    details TEXT
) ENGINE = innodb;


CREATE TABLE e_Event_Slots (
    maxSPR INT,
    maxEMT INT,
    maxFR INT,
    resEMT INT,
    resFR INT,
    event_id INT,
    FOREIGN KEY (event_id) REFERENCES e_Event(id)
) ENGINE = innodb;


CREATE TABLE e_Event_Create (
    event_id INT,
    mem_id INT,
    timeMade DATETIME,
    FOREIGN KEY (mem_id) REFERENCES e_Member(id),
    FOREIGN KEY (event_id) REFERENCES e_Event(id)
) ENGINE = innodb;


CREATE TABLE e_Signup (
    mem_id INT NOT NULL,
    event_id INT NOT NULL,
    eventRole VARCHAR(20),
    timeSignedUp DATETIME,
    FOREIGN KEY (mem_id) REFERENCES e_Member(id),
    FOREIGN KEY (event_id) REFERENCES e_Event(id)
) ENGINE = innodb;

/*
1: Worked
2: Reserved
3: Dropped
*/
CREATE TABLE e_Event_Worked (
    status INT NOT NULL,
    event_id INT,
    mem_id INT,
    FOREIGN KEY (mem_id) REFERENCES e_Member(id),
    FOREIGN KEY (event_id) REFERENCES e_Event(id)
) ENGINE = innodb;


/*
99: CPR
100: FR
101: EMT-B
102: EMT-A
103: Paramedic
*/
CREATE TABLE e_Cert (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100)
) ENGINE = innodb;


CREATE TABLE e_Cert_Assign (
    cert_id INT NOT NULL,
    member_id INT NOT NULL,
    startDate DATE,
    expireDate DATE,
    FOREIGN KEY (cert_id) REFERENCES e_Certs(id),
    FOREIGN KEY (member_id) REFERENCES e_Member(id)
) ENGINE = innodb;


CREATE TABLE e_Announcement (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title TEXT NOT NULL,
    dateTimeMade DATETIME, 
    details TEXT,
    uniqueIdentifier VARCHAR(12)
) ENGINE = innodb;


CREATE TABLE e_Anno_Creator (
    member_id INT,
    anno_id INT,
    FOREIGN KEY (member_id) REFERENCES e_Member(id),
    FOREIGN KEY (anno_id) REFERENCES e_Announcement(id)
) ENGINE = innodb;


CREATE TABLE e_Anno_Edit (
    member_id INT,
    anno_id INT,
    editTime DATETIME,
    FOREIGN KEY (member_id) REFERENCES e_Member(id),
    FOREIGN KEY (anno_id) REFERENCES e_Announcement(id)
) ENGINE = innodb;


INSERT INTO e_Cert (name, id) VALUES
("CPR", 99),
("First Responder", 100),
("EMT-B", 101),
("EMT-A", 102),
("Paramedic", 103);