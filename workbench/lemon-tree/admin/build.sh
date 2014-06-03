# php artisan migrate:make create_section_table
# php artisan migrate:make create_service_section_table
# php artisan migrate:make create_site_settings_table
# php artisan migrate:make create_site_user_table
# php artisan migrate:make create_blog_post_table
# php artisan migrate:make create_comment_table

php artisan migrate:reset
php artisan migrate
php artisan db:seed

php artisan migrate --bench="lemon-tree/admin"
php artisan db:seed --class="LemonTree\DatabaseSeeder"
