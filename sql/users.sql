CREATE DATABASE IF NOT EXISTS UsersDB;
USE UsersDB;

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    market_name VARCHAR(255) NOT NULL,
    user_email VARCHAR(255) NOT NULL,
    user_password VARCHAR(255) NOT NULL,
    user_city VARCHAR(100),
    user_district VARCHAR(100),
    user_address VARCHAR(255),
    type_of_user INT,
    user_session_token VARCHAR(512)
);

INSERT INTO users (
    first_name, last_name, user_email, user_password, user_city, user_district, user_address, type_of_user,user_session_token
) VALUES
('Saida', 'Rustamova', 'saida.rustamova@ug.bilkent.edu.tr', '123', 'Ankara', 'Çankaya', 'Address 1', '0', NULL),
('Sherzod', 'Sobirov', 'sherzod.sobirov@ug.bilkent.edu.tr', '123', 'Istanbul', 'Beşiktaş', 'Address 2', '0', NULL),
('Irem', 'Kaymak', 'irem.kaymak@ug.bilkent.edu.tr', '123', 'Antalya', 'Muratpaşa', 'Address 4', '0', NULL),
('Leyli', 'Shadurdyyeva', 'leyli@ug.bilkent.edu.tr', '123', 'Izmir', 'Konak', 'Address 3', '0', NULL);

INSERT INTO users (
    market_name, user_email, user_password, user_city, user_district, user_address, type_of_user,user_session_token
) VALUES
('Migros', 'Migros@ug.bilkent.edu.tr', '123', 'Ankara', 'Çankaya', 'Address 1', '1', NULL),
('A101', 'A101@ug.bilkent.edu.tr', '123', 'Istanbul', 'Beşiktaş', 'Address 2', '1', NULL);

-- 0 = customer
-- 1 = market
