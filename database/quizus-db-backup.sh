NOW=$(date +%Y-%m-%d)

mysqldump -u root -p#quizus@aws0805 quizus > ~/mysql-quizus/backup/quizus_db_$NOW.sql

mysqldump -u root -p#quizus@aws0805 mipcat > ~/mysql-quizus/ezeebackup/ezeeassess_db_$NOW.sql



rm ./backup/*
rm ./ezeebackup/*
