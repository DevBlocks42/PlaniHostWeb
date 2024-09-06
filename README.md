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


Initialisation de l'application web (répertoire : http(s)://SITE_RACINE/install)

![1ere-auth](https://github.com/user-attachments/assets/5edb1a55-49bf-42a1-b1ad-e4e4877bd71d)


![1ere_config_](https://github.com/user-attachments/assets/d01939a2-43dd-468f-b256-dc35c378761e)

![1ere_config_redirect](https://github.com/user-attachments/assets/c8614e17-fc62-43fe-827b-fb69a275e608)

![1er_admin_connect](https://github.com/user-attachments/assets/eb860e9d-7630-4f87-b039-80a0f96e0403)


![espace_admin](https://github.com/user-attachments/assets/3955d9f5-6006-4a3f-95b5-76bb4357ee5d)

![add_chambre](https://github.com/user-attachments/assets/73103bec-5aad-4649-84d1-81ded7041621)

![add_chambre_](https://github.com/user-attachments/assets/46779d01-c40d-4e3e-bfb5-e0c641bcef63)

![fin](https://github.com/user-attachments/assets/4c688660-9e22-46bf-83c9-6c743828a112)


