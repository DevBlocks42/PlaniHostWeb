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


Initialisation de l'application web 

![1ere-auth](https://github.com/user-attachments/assets/5edb1a55-49bf-42a1-b1ad-e4e4877bd71d)


![1ere_config_](https://github.com/user-attachments/assets/d01939a2-43dd-468f-b256-dc35c378761e)
