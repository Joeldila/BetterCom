CREATE TABLE IF NOT EXISTS users (
 id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(140) NOT NULL, email VARCHAR(190) NOT NULL UNIQUE,
 password_hash VARCHAR(255) NOT NULL, role ENUM('admin','it_admin','hod','lecturer','class_rep','student','technician') NOT NULL DEFAULT 'student',
 student_no VARCHAR(80) NULL, campus VARCHAR(100) NULL, programme VARCHAR(140) NULL,
 level_id INT NULL, group_id INT NULL, status ENUM('pending','active','disabled') NOT NULL DEFAULT 'pending',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS academic_levels (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100) NOT NULL);
CREATE TABLE IF NOT EXISTS student_groups (id INT AUTO_INCREMENT PRIMARY KEY, level_id INT NOT NULL, name VARCHAR(100) NOT NULL, UNIQUE(level_id,name));
CREATE TABLE IF NOT EXISTS modules (id INT AUTO_INCREMENT PRIMARY KEY, code VARCHAR(40), title VARCHAR(160) NOT NULL, description TEXT, level_id INT NOT NULL, group_id INT NULL, lecturer_id INT NOT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP);
CREATE TABLE IF NOT EXISTS announcements (id INT AUTO_INCREMENT PRIMARY KEY, title VARCHAR(180) NOT NULL, body TEXT NOT NULL, audience_type ENUM('all','level','group','module','staff') NOT NULL DEFAULT 'all', audience_id INT NULL, author_id INT NOT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, expires_at DATETIME NULL);
CREATE TABLE IF NOT EXISTS messages (id INT AUTO_INCREMENT PRIMARY KEY, sender_id INT NOT NULL, recipient_id INT NULL, group_id INT NULL, module_id INT NULL, body TEXT NOT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP);
CREATE TABLE IF NOT EXISTS tickets (id INT AUTO_INCREMENT PRIMARY KEY, ticket_no VARCHAR(40) UNIQUE, requester_id INT NOT NULL, category VARCHAR(100) NOT NULL, subject VARCHAR(180) NOT NULL, description TEXT NOT NULL, priority ENUM('low','medium','high','critical') DEFAULT 'medium', status ENUM('open','assigned','in_progress','referred','resolved','closed') DEFAULT 'open', assignee_id INT NULL, responsibility ENUM('college_it','external_system','academic_office') DEFAULT 'college_it', resolution TEXT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP);
CREATE TABLE IF NOT EXISTS ticket_updates (id INT AUTO_INCREMENT PRIMARY KEY, ticket_id INT NOT NULL, user_id INT NOT NULL, note TEXT NOT NULL, status VARCHAR(40), created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP);
CREATE TABLE IF NOT EXISTS meetings (id INT AUTO_INCREMENT PRIMARY KEY, title VARCHAR(180) NOT NULL, description TEXT, room_name VARCHAR(120) NOT NULL UNIQUE, host_id INT NOT NULL, module_id INT NULL, group_id INT NULL, starts_at DATETIME NULL, ends_at DATETIME NULL, meeting_type VARCHAR(60) DEFAULT 'meeting', created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP);
CREATE TABLE IF NOT EXISTS meeting_attendance (id INT AUTO_INCREMENT PRIMARY KEY, meeting_id INT NOT NULL, user_id INT NOT NULL, joined_at DATETIME DEFAULT CURRENT_TIMESTAMP, left_at DATETIME NULL);
CREATE TABLE IF NOT EXISTS assets (id INT AUTO_INCREMENT PRIMARY KEY, asset_tag VARCHAR(80) UNIQUE, name VARCHAR(160) NOT NULL, category VARCHAR(100), campus VARCHAR(100), location VARCHAR(160), condition_status VARCHAR(60) DEFAULT 'working', notes TEXT);
CREATE TABLE IF NOT EXISTS notifications (id INT AUTO_INCREMENT PRIMARY KEY, user_id INT NOT NULL, title VARCHAR(180) NOT NULL, body TEXT, link VARCHAR(255), is_read TINYINT(1) DEFAULT 0, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP);
CREATE TABLE IF NOT EXISTS audit_logs (id INT AUTO_INCREMENT PRIMARY KEY, user_id INT NULL, action VARCHAR(180) NOT NULL, details TEXT, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP);
