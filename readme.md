<p align="center"><img src="https://res.cloudinary.com/dtfbvvkyp/image/upload/v1566331377/laravel-logolockup-cmyk-red.svg" width="400"></p>


## Requirements
 -- PHP 7.2+ 
 -- MYSQL

## Instalation
Set credentials fot mysql  /config/database 

 php artisan migrate
 php artisan groups:collect 2019
 php artisan teams:collect 2019
 php artisan matches:collect 2019
 
 php artisan serve --port=[port]


#FLow
DashboardController index shows tha next upcoming match
DashboardController teamsRatio  shows each teams win/lose ratio
