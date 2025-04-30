SET foreign_key_checks = 0;

source schema.sql;
source data.sql;
\! php hashPassword.php;
source views.sql;
source sp.sql;
source triggers.sql;

SET foreign_key_checks = 1;