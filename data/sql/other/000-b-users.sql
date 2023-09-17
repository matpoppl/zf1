DROP USER IF EXISTS zf1@'127.0.0.1';
CREATE USER IF NOT EXISTS zf1@'127.0.0.1' IDENTIFIED VIA mysql_native_password USING PASSWORD('zf1pass'); 
-- ALTER USER 'bob'@'127.0.0.1' IDENTIFIED VIA mysql_native_password USING PASSWORD('pwd2');
-- MariaDB ... WITH mysql_native_password USING PASSWORD('qwe');
GRANT SELECT, INSERT, UPDATE, DELETE ON zf1.* TO zf1@'127.0.0.1';
