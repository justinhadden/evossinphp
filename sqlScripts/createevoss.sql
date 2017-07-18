CREATE TABLE Employee(
    EmpID INT NOT NULL auto_increment,
    FirstName VARCHAR(15) NOT NULL,
    LastName VARCHAR(30) NOT NULL,
    Phone CHAR(12) NOT NULL,
    Email VARCHAR(30) NOT NULL,
    ShiftCode CHAR(1) NOT NULL,
    JobCode CHAR(3) NOT NULL,
    OTHours INT NOT NULL,
    ForcedOTHours INT NOT NULL,
    ForcedRefusals INT NOT NULL,
    GrantedRefusals INT NOT NULL,
    NumForced INT NOT NULL,
    NumRefused INT NOT NULL,
    NumMandated INT NOT NULL,
    LastLogin DATETIME NOT NULL,
    PRIMARY KEY(EmpID)
)ENGINE=INNODB;

CREATE TABLE Submission(
    SubID INT NOT NULL auto_increment,
    EmpID INT NOT NULL,
    SubmissionDate DATE NOT NULL,
    Shift TINYINT NOT NULL,
    JobCode CHAR(3) NOT NULL,
    EmpComment TEXT(50),
    OTBlock TINYINT NOT NULL,
    TStamp TIMESTAMP NOT NULL,
    PRIMARY KEY(SubID),
    FOREIGN KEY(EmpID) REFERENCES Employee (EmpID) ON DELETE CASCADE
)ENGINE=INNODB;

CREATE TABLE Supervisor(
    SupID INT NOT NULL auto_increment,
    FirstName VARCHAR(15) NOT NULL,
    LastName VARCHAR(30) NOT NULL,
    Phone CHAR(12) NOT NULL,
    Email VARCHAR(30) NOT NULL,
    LastLogin DATETIME NOT NULL,
    PRIMARY KEY(SupID)
)ENGINE=INNODB;

CREATE TABLE OvertimeNeed(
    OTNeedID INT NOT NULL auto_increment,
    SupID INT NOT NULL,
    OTDate DATE NOT NULL,
    Shift TINYINT NOT NULL,
    JobCode CHAR(3) NOT NULL,
    OTBlock TINYINT NOT NULL,
    TStamp TIMESTAMP NOT NULL,
    EmpID INT,
    EmpComment TEXT(50),
    PRIMARY KEY(OTNeedID),
    FOREIGN KEY(SupID) REFERENCES Supervisor (SupID) ON DELETE CASCADE
)ENGINE=INNODB;