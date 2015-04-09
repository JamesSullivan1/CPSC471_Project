
CREATE TABLE IF NOT EXISTS detainee (
    uname           VARCHAR(64)     UNIQUE NOT NULL,
    rel_date        DATETIME            NOT NULL,

    PRIMARY KEY (uname),
    FOREIGN KEY (uname) REFERENCES people(uname) 
    ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS works (
    d_uname         VARCHAR(64)     UNIQUE NOT NULL,
    t_id            INT,

    FOREIGN KEY (d_uname) REFERENCES detainee(uname)
    ON DELETE CASCADE,
    FOREIGN KEY (t_id) REFERENCES task(id)
    ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS cell (
    num             INT,
    s_num           INT,

    FOREIGN KEY (s_num) REFERENCES section(num)
    ON DELETE CASCADE,
    PRIMARY KEY (num, s_num)
);

CREATE TABLE IF NOT EXISTS livesin (
    d_uname         CHAR(9),
    s_num           INT,
    c_num           INT,

    FOREIGN KEY (d_uname) REFERENCES detainee(uname)
    ON DELETE CASCADE,
    FOREIGN KEY (s_num) REFERENCES section(num)
    ON DELETE CASCADE,
    FOREIGN KEY (c_num) REFERENCES cell(num)
    ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS contact (
    name            VARCHAR(64)     NOT NULL,
    birthdate       DATETIME,
    relship         VARCHAR(16)     NOT NULL,
    d_sin           CHAR(9),

    FOREIGN KEY (d_uname) REFERENCES detainee(uname)
    ON DELETE CASCADE
);

