CREATE TABLE Employee(
    ID int(11) NOT NULL auto_increment,
    FirstName VARCHAR(15) NOT NULL,
    LastName VARCHAR(30) NOT NULL,
    Phone CHAR(12) NOT NULL,
    Email VARCHAR(30) NOT NULL,
    ShiftCode CHAR(1) NOT NULL,
    JobCode CHAR(3) NOT NULL,
    OTHours int(11) NOT NULL,
    ForcedOTHours int(11) NOT NULL,
    ForcedRefusals int(11) NOT NULL,
    GrantedRefusals int(11) NOT NULL,
    NumForced int(11) NOT NULL,
    NumRefused int(11) NOT NULL,
    NumMandated int(11) NOT NULL,
    LastLogin DATETIME NOT NULL,
    PRIMARY KEY(ID)
)

CREATE TABLE OvertimeSlot(
    ID int(11) NOT NULL auto_increment,
    Date DATE NOT NULL,
    Shift TINYINT NOT NULL,
    JobCode CHAR(3) NOT NULL,
    First4 TINYINT NOT NULL,
    Full8 TINYINT NOT NULL,
    Last4 TINYINT NOT NULL,
    VolMand CHAR(1),
    EmpID int(11),
    PRIMARY KEY(ID)
)*

CREATE TABLE OvertimeSubs(
    EmpID int(11) NOT NULL,
    OTID int(11) NOT NULL,
    OTBlock TINYINT NOT NULL,
    TStamp TIMESTAMP NOT NULL,
    Comment TEXT(50),
    PRIMARY KEY(EmpID, OTID),
    FOREIGN KEY(EmpID) REFERENCES Employee (ID) ON DELETE CASCADE,
    FOREIGN KEY(OTID) REFERENCES OvertimeSlot (ID) ON DELETE CASCADE
);