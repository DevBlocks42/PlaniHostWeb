#Installation

- Apache2
- Php
- Mariadb
- Composer
- Stripe

SQL : 

      CREATE DATABASE planihost;
      CREATE USER 'dbadmin'@'localhost' IDENTIFIED BY 'P@$$word1';
      USE planihost;
      SOURCE database.sql;
      GRANT SELECT, DELETE, UPDATE, INSERT ON planihost.* TO 'dbadmin'@'localhost';
      FLUSH PRIVILEGES;

