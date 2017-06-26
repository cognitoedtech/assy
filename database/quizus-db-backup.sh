NOW=$(date +%Y-%m-%d)

mysqldump -u root -p#quizus@aws0805 quizus > ~/mysql-quizus/backup/quizus_db_$NOW.sql

mysqldump -u root -p#quizus@aws0805 mipcat > ~/mysql-quizus/ezeebackup/ezeeassess_db_$NOW.sql

#AWS_ACCESS_KEY_ID
#=
#AKIAJ42JCY4MSUMUG4DA 
#AWS_SECRET_ACCESS_KEY
#=
#J0FIX20SFCHACJ4hpv5xEix2ajk3Sy0t/5UiQzOe 
#/home/ubuntu/.local/bin/aws s3 sync /home/ubuntu/mysql-quizus/backup/ s3://quizus.co/db-backups/

#AWS_ACCESS_KEY_ID
#=
#AKIAJ42JCY4MSUMUG4DA 
#AWS_SECRET_ACCESS_KEY
#=
#J0FIX20SFCHACJ4hpv5xEix2ajk3Sy0t/5UiQzOe 
#/home/ubuntu/.local/bin/aws s3 sync /home/ubuntu/mysql-quizus/ezeebackup/ s3://quizus.co/db-backups/

rm ./backup/*
rm ./ezeebackup/*

#AWS_ACCESS_KEY_ID=AKIAJU4JRLYXC25VBYGQ AWS_SECRET_ACCESS_KEY=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 /usr/local/bin/aws ses send-email --from quizus.co@gmail.com --to quizus.co@gmail.com --subject "QuizUs.co database backed up" --text "Database has been backed up!"
