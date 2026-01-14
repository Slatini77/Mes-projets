CREATE DATABASE IF NOT EXISTS fullstack_portfolio;
USE fullstack_portfolio;

SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS skills;
DROP TABLE IF EXISTS education;
DROP TABLE IF EXISTS experiences;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS admins;
SET FOREIGN_KEY_CHECKS=1;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  first_name VARCHAR(100) NOT NULL,
  last_name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL,
  phone VARCHAR(20) DEFAULT '',
  bio TEXT,
  picture VARCHAR(255) DEFAULT '',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE experiences (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  title VARCHAR(100) NOT NULL,
  company VARCHAR(100) DEFAULT '',
  start_date DATE,
  end_date DATE,
  description TEXT,
  CONSTRAINT fk_exp_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE education (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  school VARCHAR(100) NOT NULL,
  degree VARCHAR(100) DEFAULT '',
  start_date DATE,
  end_date DATE,
  description TEXT,
  CONSTRAINT fk_edu_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE skills (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  skill_name VARCHAR(100) NOT NULL,
  level ENUM('Beginner','Intermediate','Advanced','Expert') DEFAULT 'Intermediate',
  CONSTRAINT fk_skill_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO users (id, first_name, last_name, email, phone, bio, picture) VALUES
(1,'Diego','De La Torre','diego@example.com','+33 6 00 00 00 01','Étudiant EPITECH, passionné de web.','diego.svg'),
(2,'Axel','Dupont','axel@example.com','+33 6 00 00 00 02','Développeur front, amateur de design.','axel.svg'),
(3,'Aykel','Martin','aykel@example.com','+33 6 00 00 00 03','Back-end et bases de données.','aykel.svg');

INSERT INTO experiences (user_id, title, company, start_date, end_date, description) VALUES
(1,'Stagiaire Web','WebCorp','2024-06-01','2024-08-31','Intégration HTML/CSS/JS, corrections de bugs.'),
(1,'Freelance','Divers','2025-01-01',NULL,'Petits projets vitrines.'),
(2,'UI Designer','StudioX','2023-09-01','2024-07-01','Design system, maquettes Figma.'),
(3,'Dev PHP','BackLab','2024-02-01','2024-12-01','API, PDO, sécurisation.');

INSERT INTO education (user_id, school, degree, start_date, end_date, description) VALUES
(1,'EPITECH','Piscine Web','2025-09-01',NULL,'HTML, CSS, JS, PHP, MySQL, Docker'),
(2,'IUT Nice','BUT MMI','2023-09-01',NULL,'Multimédia et Internet'),
(3,'Université','Licence Info','2022-09-01','2025-06-30','Parcours développement');

INSERT INTO skills (user_id, skill_name, level) VALUES
(1,'HTML','Advanced'),(1,'CSS','Advanced'),(1,'JavaScript','Intermediate'),(1,'PHP','Intermediate'),
(2,'UI/UX','Advanced'),(2,'Figma','Advanced'),(2,'JavaScript','Intermediate'),
(3,'PHP','Advanced'),(3,'MySQL','Advanced'),(3,'Docker','Intermediate');

INSERT INTO admins (username, password) VALUES
('admin', '$2y$10$y2v2i4Jm9mD2N1zv0l2w1u6I8l3sY0n2zP1y9Yp8R8cD0v6Jp2nQ2');
