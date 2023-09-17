Learn Zend Framework v1 by creating basic cms

# Setup

- Create database 'zf1'
- Run `composer install` in 'html/app'
- Create DB
   - With ANT
     - Run `ant generate:entities:db`
   - Without ANT
     - Run `utils/scripts/00-import.bat`
- Point webserver document root to 'html/'
- Add PHP 7.1 support
- Configure database in 'html/app/configs/zend-local.php'
