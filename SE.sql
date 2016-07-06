DROP table Item;
DROP table Purchase;
Drop table Inventory;
CREATE TABLE Purchase (Pid INT AUTO_INCREMENT PRIMARY KEY, Salesmanager VARCHAR(30), Status VARCHAR(20), Pdate VARCHAR(30), Ldate VARCHAR(30), AppGM Boolean,
AppT Boolean, isReject Boolean);
CREATE TABLE Item (Iid INT AUTO_INCREMENT PRIMARY KEY, Pid INT, Make VARCHAR(20), Model VARCHAR(20), Submodel VARCHAR(20), Cyear VARCHAR(20), Color VARCHAR(20), 
Body VARCHAR(20), Quantity  INT, Price Int, Total INT, Foreign Key(Pid) References Purchase(Pid) ON DELETE CASCADE ON UPDATE CASCADE);
CREATE TABLE Inventory (VIN  VARCHAR(10) PRIMARY KEY, ctype VARCHAR(10), make VARCHAR(10), model VARCHAR(10), submodel VARCHAR(10), cyear VARCHAR(10), body VARCHAR(10),
color VARCHAR(10),stock VARCHAR(10), cdate DATE);
INSERT INTO Inventory (VIN, ctype, make, model, submodel, cyear, body, color, stock,cdate) VALUES ('124','New','Honda','Civic','Sport','2010','Sedan','White','I101', '2015-04-05');
INSERT INTO Inventory (VIN, ctype, make, model, submodel, cyear, body, color, stock,cdate) VALUES ('125','Used','Honda','CR-V','Sport','2011','SUV','Black','I102', '2015-04-05');
INSERT INTO Inventory (VIN, ctype, make, model, submodel, cyear, body, color, stock,cdate) VALUES ('126','New','Toyota','Corallo','Sport','2012','SUV','Black','I103', '2015-04-09');
INSERT INTO Inventory (VIN, ctype, make, model, submodel, cyear, body, color, stock,cdate) VALUES ('127','New','Suzuki','Swift','Dezire','2013','Sport','Black','I104', '2015-04-09');
INSERT INTO Inventory (VIN, ctype, make, model, submodel, cyear, body, color, stock,cdate) VALUES ('128','Used','Honda','CR-V','Sport','2010','SUV','Black','I105', '2015-04-10');