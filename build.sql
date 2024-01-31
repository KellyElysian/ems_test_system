

-- Creating Tables
CREATE TABLE e_Login (
    uid INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    username VARCHAR(25)
) ENGINE = innodb;


CREATE TABLE e_Member (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstName VARCHAR(20) NOT NULL,
    lastName VARCHAR(30) NOT NULL,
    points INT
) ENGINE = innodb;


-- This table is used to prevent the bloating of the member table if this system is ever officialized.
CREATE TABLE e_Info (
    member_id INT NOT NULL,
    dateSigned DATE,
    FOREIGN KEY (member_id) REFERENCES e_Member(id)
) ENGINE = innodb;


CREATE TABLE e_Event (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(35),
    dateOf DATE,
    location VARCHAR(60),
    details TEXT
) ENGINE = innodb;


CREATE TABLE e_Signup (
member_id INT NOT NULL,
event_id INT NOT NULL,
memberRole VARCHAR(20),
FOREIGN KEY (member_id) REFERENCES e_Member(id),
FOREIGN KEY (event_id) REFERENCES e_Event(id)
) ENGINE = innodb;