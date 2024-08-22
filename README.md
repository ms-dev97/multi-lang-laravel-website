# Set up the project
To set up the projects follow these steps

## Step 1
Clone the repo

## Step 2
Run composer install

## Step 3
Run php artisan key:generate

## Step 4
Run php artisan key:generate

## Step 5
Run php artisan migrate

## Step 6
Create permissions
Run php artisan db:seed --class=PermissionSeeder

## Step 7
Create super admin with the role super-admin:
Run php artisan db:seed --class=SuperadminSeeder

## Step 8 
Create admin with the role admin:
Run php artisan db:seed --class=AdminSeeder

## Step 8
Seed the website setting to the database
Run php artisan db:seed --class=SettingSeeder