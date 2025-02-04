CREATE DATABASE absence_management;

USE absence_management;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('surveillant', 'directeur') NOT NULL
);

CREATE TABLE stagiaires (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    filiere VARCHAR(100) NOT NULL,
    groupe VARCHAR(100) NOT NULL,
    academic_year varchar(255) not null,
    cin varchar(255),
    email VARCHAR(255)
);

CREATE TABLE absences (
    id INT AUTO_INCREMENT PRIMARY KEY,
    stagiaire_id int,
    filiere varchar(255) ,
    group_number INT,
    absence_date DATE NOT NULL,
    FOREIGN KEY (stagiaire_id) references stagiaires(id),
    status ENUM('absence justifiée', 'absence injustifiée', 'présence') NOT NULL,
    hours INT NOT NULL
);

 
INSERT INTO stagiaires (name, filiere, groupe, academic_year, cin, email) VALUES
('Adam Martin', 'Developpement', '201', '2024/2025', 'TJ8899', 'email@gmail.com'),
('Hanane Petit', 'Developpement', '201', '2022/2023', 'TJ8899', 'email@gmail.com'),
('Grace Robert', 'Developpement', '201', '2022/2023', 'TJ8899', 'email@gmail.com'),
('Jack Lambert', 'Developpement', '201', '2024/2025', 'TJ8899', 'email@gmail.com'),
('Fatima Benali', 'Developpement', '201', '2024/2025', 'TJ8900', 'fatima.benali@email.com'),
('Leila Benkirane', 'Developpement', '201', '2022/2023', 'TJ8903', 'leila.benkirane@email.com'),
('Khalid Benchekroun', 'Developpement', '201', '2022/2023', 'TJ8906', 'khalid.benchekroun@email.com'),
('Aisha Ouazzani', 'Developpement', '201', '2024/2025', 'TJ8909', 'aisha.ouazzani@email.com'),
('Reda Boukhari', 'Developpement', '201', '2024/2025', 'TJ8910', 'reda.boukhari@email.com'),
('Latifa Bennani', 'Developpement', '201', '2022/2023', 'TJ8913', 'latifa.bennani@email.com'),
('Karim Belghazi', 'Developpement', '201', '2022/2023', 'TJ8916', 'karim.belghazi@email.com'),
('Radia Messaoudi', 'Developpement', '201', '2024/2025', 'TJ8919', 'radia.messaoudi@email.com');

 
INSERT INTO stagiaires (name, filiere, groupe, academic_year, cin, email) VALUES
('Hamid Dupont', 'Security', '103', '2023/2024', 'TJ8899', 'email@gmail.com'),
('Haya Moreau', 'Security', '103', '2024/2025', 'TJ8899', 'email@gmail.com'),
('Hugo Vincent', 'Security', '103', '2023/2024', 'TJ8899', 'email@gmail.com'),
('Zahra Ait Mhand', 'Security', '103', '2023/2024', 'TJ8901', 'zahra.aitmhand@email.com'),
('Yassine Benmansour', 'Security', '103', '2024/2025', 'TJ8904', 'yassine.benmansour@email.com'),
('Samira Tazi', 'Security', '103', '2023/2024', 'TJ8907', 'samira.tazi@email.com'),
('Asma Lakhal', 'Security', '103', '2023/2024', 'TJ8911', 'asma.lakhal@email.com'),
('Adil Benmoussa', 'Security', '103', '2024/2025', 'TJ8914', 'adil.benmoussa@email.com'),
('Nadia Outhmani', 'Security', '103', '2023/2024', 'TJ8917', 'nadia.outhmani@email.com');

 
INSERT INTO stagiaires (name, filiere, groupe, academic_year, cin, email) VALUES
('Kamal Durand', 'TM', '300', '2022/2023', 'TJ8899', 'email@gmail.com'),
('Abd elhak Leblanc', 'TM', '300', '2022/2023', 'TJ8899', 'email@gmail.com'),
('Ivy Garnier', 'TM', '300', '2022/2023', 'TJ8899', 'email@gmail.com'),
('Ahmed Oukaid', 'TM', '300', '2022/2023', 'TJ8902', 'ahmed.oukaid@email.com'),
('Khadija El Alaoui', 'TM', '300', '2022/2023', 'TJ8905', 'khadija.elalaoui@email.com'),
('Mustapha Lahlou', 'TM', '300', '2022/2023', 'TJ8908', 'mustapha.lahlou@email.com'),
('Jamal El Ghazi', 'TM', '300', '2022/2023', 'TJ8912', 'jamal.elghazi@email.com'),
('Houda El Idrissi', 'TM', '300', '2022/2023', 'TJ8915', 'houda.elidrissi@email.com'),
('Samir Bouftas', 'TM', '300', '2022/2023', 'TJ8918', 'samir.bouftas@email.com');