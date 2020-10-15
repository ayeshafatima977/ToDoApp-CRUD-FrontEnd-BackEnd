DROP TABLE IF EXISTS Tasks;

DROP TABLE IF EXISTS Category;

DROP TABLE IF EXISTS TaskCategory;

CREATE TABLE IF NOT EXISTS TaskCategory (
    CategoryID int(10) PRIMARY KEY AUTO_INCREMENT,
    CategoryName varchar(30) NOT NULL
);

CREATE TABLE IF NOT EXISTS Task (
    TaskID int(10) PRIMARY KEY AUTO_INCREMENT,
    CategoryID int(10) NOT NULL,
    TaskName varchar(30) NOT NULL,
    TaskTimeMinutes float(4, 2) NULL,
    StartDate date NULL,
    EndDate date NULL,
    CONSTRAINT FK_Task_TaskCategory FOREIGN KEY (CategoryID) REFERENCES TaskCategory(CategoryID)
);

INSERT INTO
    `TaskCategory` (CategoryID, CategoryName)
VALUES
    (1, 'CourseWork'),
    (2, 'HouseHoldWork'),
    (3, 'Health'),
    (4, 'Leisure');

INSERT INTO
    `Task` (
        TaskID,
        CategoryID,
        TaskName,
        TaskTimeMinutes,
        StartDate,
        EndDate
    )
VALUES
    (
        NULL,
        1,
        'Learn PHP',
        60,
        '2020-10-10',
        NULL
    ),
    (
        NULL,
        1,
        'Build ToDo App Using React',
        90,
        '2020-09-20',
        '2020-10-04'
    ),
    (
        NULL,
        1,
        'Build Basic Calculator App',
        360,
        '2020-08-10',
        '2020-08-12'
    ),
    (
        NULL,
        2,
        'Grocery Shopping',
        30,
        '2020-10-18',
        '2020-10-18'
    ),
    (
        NULL,
        2,
        'Vacum',
        60,
        '2020-10-20',
        NULL
    ),
    (
        NULL,
        2,
        'Cooking',
        60,
        '2020-10-16',
        NULL
    ),
    (
        NULL,
        3,
        'Yoga',
        60,
        '2020-10-20',
        NULL
    ),
    (
        NULL,
        3,
        'Doctors Appointment',
        90,
        '2020-10-22',
        '2020-10-22'
    ),
    (
        NULL,
        4,
        'Movie Night',
        60,
        '2020-10-25',
        NULL
    ),
    (
        NULL,
        4,
        'Hiking',
        120,
        '2020-10-28',
        NULL
    );