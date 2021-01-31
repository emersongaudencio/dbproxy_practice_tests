# dbproxy_practice_tests
DB Proxy practice tests

1. Suggested grants privileges to a MySQL User for Testing purpose on the target database (MySQL/MariaDB/PostgreSQL) or Database Proxy(ProxySQL/MaxScale/Haproxy):

* Creating databases and user for Testing purpose (MySQL/MariaDB)
```
mysql -u root -p < 00_create_user_and_privs.sql
```


* Removing databases and user for Testing purpose (MySQL/MariaDB)
```
mysql -u root -p < 99_remove_user_and_privs.sql
```


* Testing if MySQL/MariaDB/PostgreSQL/Oracle is open
```
nc -zv lb-priv-proxysqlservers-84e0f2501b7e26f3.elb.us-east-1.amazonaws.com 6033
nc -zv rds1-mysql57-84e0f2501b7e26f3.us-east-1.elb.amazonaws.com 3306
```
