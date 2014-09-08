php composer.phar create-project laravel/laravel your-project-name --prefer-dist
php composer.phar install
php artisan workbench vendor/package --resources
cd workbench/vendor/package
php ../../../composer.phar install

php artisan migrate:make create_section_table
php artisan migrate:make create_service_section_table
php artisan migrate:make create_site_settings_table
php artisan migrate:make create_counter_table
php artisan migrate:make create_expense_table
php artisan migrate:make create_expense_category_table
php artisan migrate:make create_expense_source_table

php artisan migrate:make create_category_table
php artisan migrate:make create_subcategory_table
php artisan migrate:make create_good_table
php artisan migrate:make create_good_brand_table
php artisan migrate:make create_photo_table
php artisan migrate:make create_video_table

php artisan migrate:make create_site_user_table
php artisan migrate:make create_cart_position_table
php artisan migrate:make create_order_table
php artisan migrate:make create_order_position_table
php artisan migrate:make create_order_state_table
php artisan migrate:make create_metro_station_table
php artisan migrate:make create_mosobl_region_table
php artisan migrate:make create_delivery_address_table
php artisan migrate:make create_courier_table

php artisan migrate:reset
php artisan migrate
php artisan db:seed

php artisan migrate --bench="lemon-tree/admin"
php artisan db:seed --class="LemonTree\DatabaseSeeder"
