
CREATE TABLE IF NOT EXISTS employee (
    fname           VARCHAR(32)     NOT NULL,
    lname           VARCHAR(32)     NOT NULL,
    sin             CHAR(9),
    birthdate       DATE            NOT NULL,
    s_sin           CHAR(9),

    PRIMARY KEY (sin),
    FOREIGN KEY (s_sin) REFERENCES employee(sin)
    ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS emp_mname (
    mname           VARCHAR(32)     NOT NULL,
    e_sin           CHAR(9),

    FOREIGN KEY (e_sin) REFERENCES employee(sin)
);

CREATE TABLE IF NOT EXISTS dependent (
    name            VARCHAR(64)     NOT NULL,
    birthdate       DATE,
    relship         VARCHAR(16)     NOT NULL,
    e_sin           CHAR(9),

    FOREIGN KEY (e_sin) REFERENCES employee(sin)
);

CREATE TABLE IF NOT EXISTS section (
    name            VARCHAR(32)     NOT NULL,
    num             INT,
    w_sin           CHAR(9),

    PRIMARY KEY (num),
    FOREIGN KEY (w_sin) REFERENCES employee(sin)
);

CREATE TABLE IF NOT EXISTS cell (
    num             INT             NOT NULL,
    s_num           INT,

    PRIMARY KEY (num,s_num),
    FOREIGN KEY (s_num) REFERENCES section(num)
);

CREATE TABLE IF NOT EXISTS shift (
    req_role        VARCHAR(10),
    start           DATE            NOT NULL,
    end             DATE            NOT NULL,
    e_sin           CHAR(9),
    s_num           INT,

    PRIMARY KEY (e_sin),
    FOREIGN KEY (e_sin) REFERENCES employee(sin),
    FOREIGN KEY (s_num) REFERENCES section(num)
);

CREATE TABLE IF NOT EXISTS task (
    id              INT,
    descr           VARCHAR(512),
    start           DATE            NOT NULL,
    end             DATE            NOT NULL,
    s_sin           CHAR(9),
    s_num           INT,

    PRIMARY KEY (id),
    FOREIGN KEY (s_sin) REFERENCES employee(sin),
    FOREIGN KEY (s_num) REFERENCES section(num)
);

CREATE TABLE IF NOT EXISTS equipment (
    name            VARCHAR(32)     NOT NULL,
    t_id            INT,

    FOREIGN KEY (t_id) REFERENCES task(id)
);

CREATE TABLE IF NOT EXISTS detainee (
    fname           VARCHAR(32)     NOT NULL,
    lname           VARCHAR(32)     NOT NULL,
    sin             CHAR(9),
    birthdate       DATE            NOT NULL,
    rel_date        DATE            NOT NULL,
    -- CELL?

    PRIMARY KEY (sin)
);

CREATE TABLE IF NOT EXISTS works (
    d_sin           CHAR(9),
    t_id            INT,

    FOREIGN KEY (d_sin) REFERENCES detainee(sin),
    FOREIGN KEY (t_id) REFERENCES task(id)
);

CREATE TABLE IF NOT EXISTS livesin (
    d_sin           CHAR(9),
    s_num           INT,
    c_num           INT,

    FOREIGN KEY (d_sin) REFERENCES detainee(sin),
    FOREIGN KEY (s_num) REFERENCES section(num),
    FOREIGN KEY (c_num) REFERENCES cell(num)
);

CREATE TABLE IF NOT EXISTS contact (
    name            VARCHAR(64)     NOT NULL,
    birthdate       DATE,
    relship         VARCHAR(16)     NOT NULL,
    d_sin           CHAR(9),

    FOREIGN KEY (d_sin) REFERENCES detainee(sin)
);
