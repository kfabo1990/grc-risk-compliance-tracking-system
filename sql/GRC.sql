-- GRC - Final Project

-- Drop all tables, so it can be rerun safely
DROP TABLE IF EXISTS Evaluates;
DROP TABLE IF EXISTS Satisfies;
DROP TABLE IF EXISTS Mitigates;
DROP TABLE IF EXISTS Audit;
DROP TABLE IF EXISTS CompReq;
DROP TABLE IF EXISTS Control;
DROP TABLE IF EXISTS Risk;
DROP TABLE IF EXISTS Employee;
DROP TABLE IF EXISTS Department;

-- Create Department table
CREATE TABLE Department (
    dept_id INT,
    dept_name VARCHAR(50) NOT NULL,
    PRIMARY KEY (dept_id)
);

-- Create Employee table
-- Dept_id is NOT NULL because of total participation (each employee must belong to a department)
-- ON UPDATE CASCADE ensures that if dept_id changes in Department, it is updated here
CREATE TABLE Employee (
    employee_id INT,
    employee_name VARCHAR(50) NOT NULL,
    job_title VARCHAR(50) NOT NULL,
    dept_id INT NOT NULL,
    PRIMARY KEY (employee_id),
    FOREIGN KEY (dept_id) REFERENCES Department(dept_id)
        ON UPDATE CASCADE
);

-- Create Risk table
-- employee_id is NOT NULL because each risk must have exactly one employee owner
-- ON UPDATE CASCADE ensures that if employee_id changes in Employee, it is updated here
CREATE TABLE Risk (
    risk_id INT,
    risk_title VARCHAR(100) NOT NULL,
    risk_level VARCHAR(20) NOT NULL,
    risk_status VARCHAR(20) NOT NULL,
    employee_id INT NOT NULL,
    PRIMARY KEY (risk_id),
    FOREIGN KEY (employee_id) REFERENCES Employee(employee_id)
        ON UPDATE CASCADE
);

-- Create Control table
-- employee_id is NOT NULL because each control must have exactly one employee owner
-- ON UPDATE CASCADE ensures that if employee_id changes in Employee, it is updated here
CREATE TABLE Control (
    control_id INT,
    control_name VARCHAR(100) NOT NULL,
    control_status VARCHAR(20) NOT NULL,
    employee_id INT NOT NULL,
    PRIMARY KEY (control_id),
    FOREIGN KEY (employee_id) REFERENCES Employee(employee_id)
        ON UPDATE CASCADE
);

-- Create CompReq table
CREATE TABLE CompReq (
    req_id INT,
    frw_name VARCHAR(50) NOT NULL,
    req_code VARCHAR(30) NOT NULL,
    PRIMARY KEY (req_id)
);

-- Create Audit table
CREATE TABLE Audit (
    audit_id INT,
    audit_name VARCHAR(100) NOT NULL,
    audit_date DATE NOT NULL,
    PRIMARY KEY (audit_id)
);

-- Create Mitigates table
-- ON UPDATE CASCADE ensures that if risk_id or control_id changes in parent tables,
-- the changes are reflected here
CREATE TABLE Mitigates (
    risk_id INT,
    control_id INT,
    PRIMARY KEY (risk_id, control_id),
    FOREIGN KEY (risk_id) REFERENCES Risk(risk_id)
        ON UPDATE CASCADE,
    FOREIGN KEY (control_id) REFERENCES Control(control_id)
        ON UPDATE CASCADE
);

-- Create Satisfies table
-- ON UPDATE CASCADE ensures that if control_id or req_id changes in parent tables,
-- the changes are reflected here
CREATE TABLE Satisfies (
    control_id INT,
    req_id INT,
    PRIMARY KEY (control_id, req_id),
    FOREIGN KEY (control_id) REFERENCES Control(control_id)
        ON UPDATE CASCADE,
    FOREIGN KEY (req_id) REFERENCES CompReq(req_id)
        ON UPDATE CASCADE
);

-- Create Evaluates table
-- ON UPDATE CASCADE ensures that if audit_id or control_id changes in parent tables,
-- the changes are reflected here
CREATE TABLE Evaluates (
    audit_id INT,
    control_id INT,
    finding_status VARCHAR(20) NOT NULL,
    PRIMARY KEY (audit_id, control_id),
    FOREIGN KEY (audit_id) REFERENCES Audit(audit_id)
        ON UPDATE CASCADE,
    FOREIGN KEY (control_id) REFERENCES Control(control_id)
        ON UPDATE CASCADE
);

-- Insert data into Department
INSERT INTO Department (dept_id, dept_name) VALUES
(10, 'IT'),
(20, 'Finance'),
(30, 'Human Resources'),
(40, 'Operations'),
(50, 'Legal'),
(60, 'Compliance');

-- Insert data into Employee
INSERT INTO Employee (employee_id, employee_name, job_title, dept_id) VALUES
(101, 'Alice Johnson', 'IT Security Manager', 10),
(102, 'Brian Smith', 'Finance Manager', 20),
(103, 'Catherine Lee', 'HR Coordinator', 30),
(104, 'David Brown', 'Operations Analyst', 40),
(105, 'Emily Davis', 'Legal Counsel', 50),
(106, 'Frank Miller', 'Compliance Officer', 60);

-- Insert data into Risk
INSERT INTO Risk (risk_id, risk_title, risk_level, risk_status, employee_id) VALUES
(201, 'Unauthorized System Access', 'High', 'Open', 101),
(202, 'Financial Reporting Errors', 'Medium', 'Open', 102),
(203, 'Employee Data Exposure', 'High', 'Mitigated', 103),
(204, 'Operational Process Failure', 'Low', 'Open', 104),
(205, 'Contract Compliance Risk', 'Medium', 'Closed', 105),
(206, 'Regulatory Noncompliance', 'High', 'Open', 106);

-- Insert data into Control
INSERT INTO Control (control_id, control_name, control_status, employee_id) VALUES
(301, 'Multi-Factor Authentication', 'Implemented', 101),
(302, 'Financial Report Review Procedure', 'Implemented', 102),
(303, 'Employee Data Access Restrictions', 'In Progress', 103),
(304, 'Operations Continuity Checklist', 'Implemented', 104),
(305, 'Contract Review and Approval Process', 'Implemented', 105),
(306, 'Regulatory Compliance Monitoring', 'In Progress', 106);

-- Insert data into CompReq
INSERT INTO CompReq (req_id, frw_name, req_code) VALUES
(401, 'NIST', 'AC-2'),
(402, 'NIST', 'IA-2'),
(403, 'ISO 27001', 'A.5.15'),
(404, 'ISO 27001', 'A.8.15'),
(405, 'SOC 2', 'CC6.1'),
(406, 'GLBA', 'Safeguards Rule');

-- Insert data into Audit
INSERT INTO Audit (audit_id, audit_name, audit_date) VALUES
(501, 'Q1 Internal Control Audit', '2026-01-15'),
(502, 'Access Management Review', '2026-02-10'),
(503, 'Backup and Recovery Audit', '2026-02-28'),
(504, 'Vendor Risk Assessment', '2026-03-12'),
(505, 'Compliance Readiness Audit', '2026-03-25'),
(506, 'Annual Security Control Review', '2026-04-05');

-- Insert data into Mitigates
INSERT INTO Mitigates (risk_id, control_id) VALUES
(201, 301),  -- Unauthorized System Access -> Multi-Factor Authentication
(201, 303),  -- Unauthorized System Access -> Employee Data Access Restrictions

(202, 302),  -- Financial Reporting Errors -> Financial Report Review Procedure

(203, 303),  -- Employee Data Exposure -> Employee Data Access Restrictions

(204, 304),  -- Operational Process Failure -> Operations Continuity Checklist

(205, 305),  -- Contract Compliance Risk -> Contract Review and Approval Process

(206, 306);  -- Regulatory Noncompliance -> Regulatory Compliance Monitoring

-- Insert data into Satisfies
INSERT INTO Satisfies (control_id, req_id) VALUES
(301, 402),  -- Multi-Factor Authentication -> NIST IA-2

(302, 406),  -- Financial Report Review Procedure -> GLBA Safeguards Rule

(303, 401),  -- Employee Data Access Restrictions -> NIST AC-2
(303, 405),  -- Employee Data Access Restrictions -> SOC 2 CC6.1

(304, 404),  -- Operations Continuity Checklist -> ISO 27001 A.8.15

(305, 403),  -- Contract Review and Approval Process -> ISO 27001 A.5.15

(306, 405);  -- Regulatory Compliance Monitoring -> SOC 2 CC6.1

-- Insert data into Evaluates
INSERT INTO Evaluates (audit_id, control_id, finding_status) VALUES
(501, 301, 'Pass'),  -- Q1 Internal Control Audit -> MFA
(501, 302, 'Pass'),  -- Q1 Internal Control Audit -> Financial Review

(502, 301, 'Fail'),  -- Access Management Review -> MFA
(502, 303, 'Pass'),  -- Access Management Review -> Data Access Restrictions

(503, 304, 'Pass'),  -- Backup and Recovery Audit -> Continuity Checklist

(504, 305, 'Pass'),  -- Vendor Risk Assessment -> Contract Review

(505, 306, 'Fail'),  -- Compliance Readiness Audit -> Compliance Monitoring

(506, 301, 'Pass');  -- Annual Security Control Review -> MFA
