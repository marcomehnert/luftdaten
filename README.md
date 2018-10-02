# luftdaten MySQL PHP API
PHP API to store Data from luftdaten sensors in MySQL

# Configuration
1. Please change the database configuration parameters according to your MySQL server and create the table `sensor_data` within that database using the SQL script database_tabble.sql
2. Upload the file to an location that is accessible for your sensor via http(s) and has PHP with PDO enabled
3. Rename the file api.php to something more cryptic like b0dcdb162c700c7f4397298bccb968a8.php (e.g. the md5 hash of a phrase of your choice + .php to avoid calling your api by unauthorized machines.
4. Put the chosen name into the configuration of your sensor
    * Server: yourdomain.tld
    * Pfad: /path/to/script/b0dcdb162c700c7f4397298bccb968a8.php (from our example above)
    * Port: 80 (or 443 in case of standard https)
    * leave user and password empty because we use the cryptic name to protect the api from random access

If you have any further questions or hints feel free to ask.