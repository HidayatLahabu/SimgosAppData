SimgosAppData is an extension module designed to complement the Simgos Kemenkes application. It integrates seamlessly with the existing simgos database for data management while maintaining a separate usersimgos database dedicated to user information.

Deployment Overview:

    SimgoAppData has only tested in Linux OS
    Server: Ubuntu 22.04
    Database: MySQL
    Multiple Database Setup: Configured through the .env file

Deployment Instructions

    Navigate to the deployment directory:
        Linux: /var/www/html/SimgoAppData
        
    Environment Setup:
        Copy .env.example to .env.
        Customize the .env file according to the provided instructions.

    Install Dependencies:
        Run composer install to install PHP dependencies.
        Run npm install to install Node.js dependencies.

    Generate Application Key:
        Run php artisan key:generate.

    Build Frontend Assets:
        Run npm run build to compile assets for production. This step eliminates the need to run npm run dev on the server.

Database Setup

In the database folder, you'll find usersimgos.sql, which contains the structure for the usersimgos database. To set it up:

    Create the usersimgos database on your MySQL server.
    Import the usersimgos.sql file using one of the following methods:
        Terminal: Run mysql -u [username] -p usersimgos < usersimgos.sql.
        GUI Tools: Use DBeaver, HeidiSQL, or a similar application.

Customization

To change the hospital name, update the HOSPITAL_NAME value in the .env file.
Updating the Application

To pull the latest updates:

    Linux: Navigate to /var/www/html/SimgosAppData and run git pull origin master in the terminal.
    Windows: If deployed on Windows, re-clone the repository and repeat steps 2 to 7 above.

Pro Tip: Fork this repository to stay up-to-date with the latest developments and enhancements in real time.

Notes:
NIK and other Identity Number in frontend has been manipulate when display in table
