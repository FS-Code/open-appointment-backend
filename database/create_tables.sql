CREATE DATABASE open_appointment;

USE open_appointment;

CREATE TABLE users (
                       id INT AUTO_INCREMENT PRIMARY KEY,
                       email VARCHAR(255) NOT NULL,
                       password VARCHAR(255) NOT NULL
);

CREATE TABLE week_days (
                           id INT AUTO_INCREMENT PRIMARY KEY,
                           start_time TIME NOT NULL,
                           end_time TIME NOT NULL
);

CREATE TABLE business_hours (
                                id INT AUTO_INCREMENT PRIMARY KEY,
                                monday INT,
                                tuesday INT,
                                wednesday INT,
                                thursday INT,
                                friday INT,
                                saturday INT,
                                sunday INT,
                                FOREIGN KEY (monday) REFERENCES week_days(id),
                                FOREIGN KEY (tuesday) REFERENCES week_days(id),
                                FOREIGN KEY (wednesday) REFERENCES week_days(id),
                                FOREIGN KEY (thursday) REFERENCES week_days(id),
                                FOREIGN KEY (friday) REFERENCES week_days(id),
                                FOREIGN KEY (saturday) REFERENCES week_days(id),
                                FOREIGN KEY (sunday) REFERENCES week_days(id)
);

CREATE TABLE buffers (
                         id INT AUTO_INCREMENT PRIMARY KEY,
                         before_time INT NOT NULL,
                         after_time INT NOT NULL
);

CREATE TABLE services (
                          id INT AUTO_INCREMENT PRIMARY KEY,
                          name VARCHAR(255) NOT NULL,
                          location VARCHAR(255) NOT NULL,
                          details TEXT NOT NULL,
                          duration INT NOT NULL,
                          business_hours_id INT NOT NULL,
                          buffer_id INT NOT NULL,
                          FOREIGN KEY (business_hours_id) REFERENCES business_hours(id),
                          FOREIGN KEY (buffer_id) REFERENCES buffers(id)
);

CREATE TABLE customers (
                           id INT AUTO_INCREMENT PRIMARY KEY,
                           name VARCHAR(255) NOT NULL,
                           email VARCHAR(255) NOT NULL
);

CREATE TABLE appointments (
                              id INT AUTO_INCREMENT PRIMARY KEY,
                              service_id INT NOT NULL,
                              customer_id INT NOT NULL,
                              starts_at TIMESTAMP NOT NULL,
                              ends_at TIMESTAMP NOT NULL,
                              created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                              FOREIGN KEY (service_id) REFERENCES services(id),
                              FOREIGN KEY (customer_id) REFERENCES customers(id)
);
