
DROP DATABASE c471;
CREATE DATABASE c471;
USE c471;

CREATE TABLE IF NOT EXISTS people (
    uname           VARCHAR(64)     UNIQUE NOT NULL,
    pass            CHAR(40)        NOT NULL,
    fname           VARCHAR(32)     NOT NULL,
    lname           VARCHAR(32)     NOT NULL,
    birthdate       DATE,

    PRIMARY KEY (uname)
);

CREATE TABLE IF NOT EXISTS employee (
    sin             CHAR(9),
    s_sin           CHAR(9),
    uname           VARCHAR(64)     UNIQUE NOT NULL,

    PRIMARY KEY (sin),
    FOREIGN KEY (uname) REFERENCES people(uname)
    ON DELETE CASCADE,
    FOREIGN KEY (s_sin) REFERENCES employee(sin)
    ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS mname (
    mname           VARCHAR(32)     NOT NULL,
    uname           CHAR(9)         NOT NULL,

    FOREIGN KEY (uname) REFERENCES people(uname)
    ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS dependent (
    fname           VARCHAR(64),
    lname           VARCHAR(64),
    birthdate       DATE,
    relship         VARCHAR(16)     NOT NULL,
    e_sin           CHAR(9),

    PRIMARY KEY (fname, birthdate, e_sin),
    FOREIGN KEY (e_sin) REFERENCES employee(sin)
    ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS section (
    name            VARCHAR(32)     NOT NULL,
    num             INT,
    w_sin           CHAR(9)         NOT NULL,

    PRIMARY KEY (num),
    FOREIGN KEY (w_sin) REFERENCES employee(sin)
    ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS cell (
    num             INT,
    s_num           INT,

    PRIMARY KEY (num, s_num),
    FOREIGN KEY (s_num) REFERENCES section(num)
    ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS shift (
    req_role        VARCHAR(10),
    start           DATETIME,
    end             DATETIME        NOT NULL,
    e_sin           CHAR(9),
    s_num           INT             NOT NULL,

    PRIMARY KEY (e_sin, start),
    FOREIGN KEY (e_sin) REFERENCES employee(sin)
    ON DELETE CASCADE,
    FOREIGN KEY (s_num) REFERENCES section(num)
    ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS task (
    id              INT,
    descr           VARCHAR(512),
    start           DATETIME        NOT NULL,
    end             DATETIME        NOT NULL,
    s_sin           CHAR(9)         NOT NULL,
    s_num           INT             NOT NULL,

    PRIMARY KEY (id),
    FOREIGN KEY (s_sin) REFERENCES employee(sin)
    ON DELETE CASCADE,
    FOREIGN KEY (s_num) REFERENCES section(num)
    ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS equipment (
    name            VARCHAR(32)     NOT NULL,
    t_id            INT             NOT NULL,

    FOREIGN KEY (t_id) REFERENCES task(id)
    ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS detainee (
    uname           VARCHAR(64),
    rel_date        DATE            NOT NULL,
    c_num           INT             NOT NULL,
    cs_num          INT             NOT NULL,

    PRIMARY KEY (uname),
    FOREIGN KEY (uname) REFERENCES people(uname)
    ON DELETE CASCADE,
    FOREIGN KEY (c_num, cs_num) REFERENCES cell(num, s_num)
    ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS works (
    d_uname         VARCHAR(64)     NOT NULL,
    t_id            INT             NOT NULL,

    FOREIGN KEY (d_uname) REFERENCES detainee(uname)
    ON DELETE CASCADE,
    FOREIGN KEY (t_id) REFERENCES task(id)
    ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS contact (
    name            VARCHAR(64),
    birthdate       DATE,
    relship         VARCHAR(16)     NOT NULL,
    d_uname         VARCHAR(64),

    PRIMARY KEY (name, birthdate, d_uname),
    FOREIGN KEY (d_uname) REFERENCES detainee(uname)
    ON DELETE CASCADE
);
