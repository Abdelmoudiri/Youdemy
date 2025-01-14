-- Création de la base de données
CREATE DATABASE youdemy;
DROP DATABASE youdemy;

USE youdemy;

-- Table des utilisateurs
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    lastname VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('visitor', 'student', 'teacher', 'admin') DEFAULT 'visitor',
    status ENUM('pending', 'active', 'suspended') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des cours
CREATE TABLE courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    content TEXT,
    category_id INT,
    teacher_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (teacher_id) REFERENCES users(id) ON DELETE CASCADE on update cascade,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE on update cascade
);

-- Table des catégories
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des tags
CREATE TABLE tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table pivot entre les cours et les tags (relation many-to-many)
CREATE TABLE course_tags (
    course_id INT NOT NULL,
    tag_id INT NOT NULL,
    PRIMARY KEY (course_id, tag_id),
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
);

-- Table des inscriptions aux cours
CREATE TABLE enrollments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    course_id INT NOT NULL,
    enrolled_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);


-- Insérer des utilisateurs
INSERT INTO users (username, email, password, role, status) VALUES
('admin', 'admin@youdemy.com', MD5('password'), 'admin', 'active'),
('teacher1', 'teacher1@youdemy.com', MD5('password'), 'teacher', 'active'),
('student1', 'student1@youdemy.com', MD5('password'), 'student', 'active');

-- Insérer des catégories
INSERT INTO categories (name) VALUES
('Développement Web'),
('Design'),
('Marketing Digital');

-- Insérer des tags
INSERT INTO tags (name) VALUES
('PHP'), 
('JavaScript'), 
('HTML'), 
('CSS'), 
('SEO');

-- Insérer des cours
INSERT INTO courses (title, description, content, category_id, teacher_id) VALUES
('Apprendre PHP pour débutants', 'Un cours complet pour apprendre PHP', 'Contenu vidéo...', 1, 2),
('Introduction à JavaScript', 'Cours interactif pour les débutants', 'Contenu vidéo...', 1, 2);

-- Associer des tags aux cours
INSERT INTO course_tags (course_id, tag_id) VALUES
(1, 1), -- PHP pour le cours 1
(2, 2), -- JavaScript pour le cours 2
(2, 3), -- HTML pour le cours 2
(2, 4); -- CSS pour le cours 2

-- Inscrire des étudiants aux cours
INSERT INTO enrollments (student_id, course_id) VALUES
(3, 1), -- Étudiant 1 inscrit au cours 1
(3, 2); -- Étudiant 1 inscrit au cours 2



SELECT * from course_tags;