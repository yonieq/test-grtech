# Install

1. clone this repo `git clone https://github.com/yonieq/test-grtech.git`
2. sign in folder project on your terminal or command prompt
3. next `composer install`
4. next `npm install`
5. `npm run build`
6. `cp -r .env.example .env`
7. set your database in your .env file
8. migrate data & seeder `php artisan migrate:fresh --seed`
9. and `php artisan serve`

# Notes

-   setting APP_URL in folder .env according to the running application will affect the rendering of the image

# Authentication User

`User`

-   email : user@grtech.com
-   password : password

`Admin`

-   email : admin@grtech.com
-   password : password
