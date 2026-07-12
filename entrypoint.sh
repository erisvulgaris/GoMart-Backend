#!/bin/bash
# Disable exit on error to allow graceful error logging and Apache start
set +e

echo "=== CityLoop Startup Script ==="

# Wait for MySQL database container to start
echo "Waiting for MySQL database at host 'db' to become ready..."
until mysql -h"db" -u"$MYSQL_USER" -p"$MYSQL_PASSWORD" --ssl=0 -e "SELECT 1"; do
    echo "MySQL is unavailable - sleeping..."
    sleep 2
done
echo "MySQL is up and online!"

# Check if the database has been seeded
# We do this by checking if the 'settings' table exists
DB_EXISTS=$(mysql -h"db" -u"$MYSQL_USER" -p"$MYSQL_PASSWORD" --ssl=0 -D"$MYSQL_DATABASE" -e "SHOW TABLES LIKE 'settings';" 2>/dev/null | grep settings || true)

if [ -z "$DB_EXISTS" ]; then
    echo "Database settings table not found. Cleaning existing tables to ensure a fresh import..."
    mysql -h"db" -u"$MYSQL_USER" -p"$MYSQL_PASSWORD" --ssl=0 -N -D"$MYSQL_DATABASE" -e "SHOW TABLES;" | while read table; do
        mysql -h"db" -u"$MYSQL_USER" -p"$MYSQL_PASSWORD" --ssl=0 -D"$MYSQL_DATABASE" -e "SET FOREIGN_KEY_CHECKS = 0; DROP TABLE IF EXISTS \`$table\`;"
    done
    echo "Database cleaned. Seeding 'database.sql' now..."
    if mysql -h"db" -u"$MYSQL_USER" -p"$MYSQL_PASSWORD" --ssl=0 "$MYSQL_DATABASE" < /var/www/html/database/database.sql; then
        echo "Database seeding completed successfully!"
        
        # Seed database with the latest scraped catalog from import_products.json (Only on fresh install)
        echo "Importing products to database..."
        php spark db:seed ProductImportSeeder
    else
        echo "ERROR: Database seeding failed! SQL import returned non-zero exit code."
    fi
else
    echo "Database already seeded. Skipping SQL import and product seeding."
fi

# Apply active production database overrides
echo "Applying active production database overrides..."
mysql -h"db" -u"$MYSQL_USER" -p"$MYSQL_PASSWORD" --ssl=0 -D"$MYSQL_DATABASE" -e "UPDATE settings SET value='AIzaSyAmX29-nyb3BDTtovxvhaJR_u82fphs-6M' WHERE \`key\`='map_api_key';"
mysql -h"db" -u"$MYSQL_USER" -p"$MYSQL_PASSWORD" --ssl=0 -D"$MYSQL_DATABASE" -e "UPDATE settings SET value='https://cityloopapp.com' WHERE \`key\`='website';"
mysql -h"db" -u"$MYSQL_USER" -p"$MYSQL_PASSWORD" --ssl=0 -D"$MYSQL_DATABASE" -e "UPDATE settings SET value='CityLoop' WHERE \`key\`='business_name';"
mysql -h"db" -u"$MYSQL_USER" -p"$MYSQL_PASSWORD" --ssl=0 -D"$MYSQL_DATABASE" -e "UPDATE settings SET value='info@cityloopapp.com' WHERE \`key\`='email';"
mysql -h"db" -u"$MYSQL_USER" -p"$MYSQL_PASSWORD" --ssl=0 -D"$MYSQL_DATABASE" -e "UPDATE settings SET value='{\"name\":\"CityLoop\",\"host\":\"smtp.hostinger.com\",\"username\":\"YOUR_SMTP_USERNAME\",\"password\":\"YOUR_SMTP_PASSWORD\",\"port\":\"465\",\"encryption\":\"ssl\"}' WHERE \`key\`='mail_config';"
mysql -h"db" -u"$MYSQL_USER" -p"$MYSQL_PASSWORD" --ssl=0 -D"$MYSQL_DATABASE" -e "UPDATE settings SET value='    <div><h1>About Us</h1>    <p>Welcome to <strong>CityLoop</strong>, your premium hyperlocal quick-commerce platform. We deliver fresh groceries and daily essentials to your doorstep in minutes.</p>    <h2>Our Mission</h2>    <p>We aim to simplify shopping with a user-friendly experience, secure payments, and reliable deliveries.</p>    <p><strong>Email:</strong> info@cityloopapp.com</p><p>Thank you for choosing <b>CityLoop</b> 🚀</p></div>' WHERE \`key\`='customer_app_about';"
mysql -h"db" -u"$MYSQL_USER" -p"$MYSQL_PASSWORD" --ssl=0 -D"$MYSQL_DATABASE" -e "UPDATE settings SET value='<h3><strong>About CityLoop Delivery App</strong></h3><p>...</p>' WHERE \`key\`='delivery_app_about';"

# Clear CodeIgniter cache to ensure settings overrides are loaded
php spark cache:clear


# Ensure CodeIgniter writable subdirectories and public uploads exist and have correct permissions
mkdir -p /var/www/html/writable/logs
mkdir -p /var/www/html/writable/cache
mkdir -p /var/www/html/writable/session
mkdir -p /var/www/html/writable/uploads
mkdir -p /var/www/html/public/uploads
chown -R www-data:www-data /var/www/html/writable /var/www/html/public/uploads
chmod -R 777 /var/www/html/writable /var/www/html/public/uploads

# Run the parent image entrypoint command (Apache foreground)
echo "Starting Apache web server..."
exec apache2-foreground
