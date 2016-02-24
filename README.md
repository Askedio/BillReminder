# BillReminder
A simple Laravel and Google Calendar based bill reminder


# Install
Clone repository

    composer install

Create .env

    APP_ENV=local
    APP_DEBUG=true
    APP_KEY=...
    APP_URL=http://localhost
     
    DB_HOST=127.0.0.1
    DB_DATABASE=...
    DB_USERNAME=...
    DB_PASSWORD=...
      
    CACHE_DRIVER=file
    SESSION_DRIVER=file
    QUEUE_DRIVER=sync
    
    REDIS_HOST=127.0.0.1
    REDIS_PASSWORD=null
    REDIS_PORT=6379
      
    MAIL_DRIVER=smtp
    MAIL_HOST=mailtrap.io
    MAIL_PORT=2525
    MAIL_USERNAME=null
    MAIL_PASSWORD=null
    MAIL_ENCRYPTION=null
   
    GOOGLE_API_KEY=
   
    GOOGLE_CLIENT_ID=<oauth client id>
    GOOGLE_SECRET=<oauth secret>
    GOOGLE_REDIRECT=http://localhost:8000/authorize
        
        
Google API Key is optinal

After .env is created, migrate

    php artisan migrate

Serv

    php artisan serv
    
Browse to the test server and authenticate, you should be asked to create a calendar, then add events. Errors are not reported, if the key errors or token is invalid you will be directed back to the home page.
