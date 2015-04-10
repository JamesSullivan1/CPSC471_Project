
BEGIN;

INSERT INTO people VALUES ('mary',SHA1('poppins'),'mary','poppins',NULL);
INSERT INTO employee VALUES ('123456789',NULL,'mary');

INSERT INTO people VALUES ('joe',SHA1('joe'),'joe','smith',NULL);
INSERT INTO employee VALUES ('111111111','123456789','joe');

INSERT INTO people VALUES ('joel',SHA1('joel'),'Jorbel','James',NULL);
INSERT INTO employee VALUES ('112233445',NULL,'joel');
INSERT INTO dependent VALUES ('Vincent', 'Van Gogh', '1700-01-01','Son','112233445');


INSERT INTO people VALUES ('canwe',SHA1('yes'),'Bob','Builder',NULL);
INSERT INTO employee VALUES ('234567897',NULL,'canwe');

INSERT INTO people VALUES ('jamie',SHA1('sully'),'Jamie','Sullicar',NULL);
INSERT INTO employee VALUES ('111111112',NULL,'jamie');

INSERT INTO people VALUES ('jeffie',SHA1('james'),'Jeff','Ruler',NULL);
INSERT INTO employee VALUES ('314159265',NULL,'jeffie');
INSERT INTO dependent VALUES ('Danny', 'Darrow', '1992-09-10','Whatever','314159265');
INSERT INTO dependent VALUES ('Danger', 'Joe','2000-01-01','Brother','314159265');
INSERT INTO dependent VALUES ('Sammy', 'Straightedge','1998-05-17','Advisor','314159265');

INSERT INTO section VALUES ('Cafeteria',1,'123456789');
INSERT INTO section VALUES ('Cell Block A',2,'234567897');
INSERT INTO cell VALUES (1, 2);
INSERT INTO cell VALUES (2, 2);
INSERT INTO cell VALUES (3, 2);
INSERT INTO cell VALUES (4, 2);
INSERT INTO cell VALUES (5, 2);
INSERT INTO cell VALUES (6, 2);
INSERT INTO cell VALUES (7, 2);
INSERT INTO cell VALUES (8, 2);
INSERT INTO section VALUES ('Cell Block B',3,'111111112');
INSERT INTO cell VALUES (1, 3);
INSERT INTO cell VALUES (2, 3);
INSERT INTO cell VALUES (3, 3);
INSERT INTO cell VALUES (4, 3);

INSERT INTO people VALUES ('bob',SHA1('bob'),'bob','bob',NULL);
INSERT INTO detainee VALUES ('bob','1900-01-01', 1, 2);

INSERT INTO people VALUES ('tweetybird992',SHA1(''),'Sally','Shanks',NULL);
INSERT INTO detainee VALUES ('tweetybird992','2020-12-12', 2, 2);

INSERT INTO people VALUES ('daredevil420',SHA1(''),'Henry','Oh',NULL);
INSERT INTO detainee VALUES ('daredevil420','1234-12-12', 1, 3);
INSERT INTO contact VALUES ('Helga','Oh','1233-06-23','Spouse','daredevil420');
INSERT INTO contact VALUES ('Alice','Oh','1556-08-03','Son','daredevil420');
INSERT INTO contact VALUES ('Sergio','Oh','1779-10-04','Daughter','daredevil420');

INSERT INTO people VALUES ('wow',SHA1('wow'),'Fun','Project',NULL);
INSERT INTO detainee VALUES ('wow','2015-04-13', 3, 2);
INSERT INTO contact VALUES ('great','job','2015-04-10','excellent','wow');
INSERT INTO contact VALUES ('nice','work','2015-04-11','well done','wow');

INSERT INTO people VALUES ('longjonny',SHA1('booty'),'Long-John','Silver',NULL);
INSERT INTO detainee VALUES ('longjonny','1883-04-09', 4, 2);
INSERT INTO contact VALUES ('Black','Pearl','1903-03-14','Ship','longjonny');

INSERT INTO people VALUES ('thathighguy',SHA1('420'),'Up','High',NULL);
INSERT INTO detainee VALUES ('thathighguy','2024-04-20', 5, 2);
INSERT INTO contact VALUES ('Billy','Bouncer','2006-05-02','Associate','thathighguy');

INSERT INTO people VALUES ('biglaffs',SHA1('haha'),'Adrian','Sanders',NULL);
INSERT INTO detainee VALUES ('biglaffs','2000-01-05', 5, 2);
INSERT INTO contact VALUES ('Joy','Gilfried','2000-03-03','Friend','biglaffs');
INSERT INTO contact VALUES ('Buddy','Marrison','1998-11-08','Friend','biglaffs');

INSERT INTO people VALUES ('susie',SHA1('sansan'),'Susan','San',NULL);
INSERT INTO detainee VALUES ('susie','2016-01-02', 2, 2);

COMMIT;
