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
    else
        echo "ERROR: Database seeding failed! SQL import returned non-zero exit code."
    fi
else
    echo "Database already seeded. Skipping SQL import."
fi

# Ensure CodeIgniter writable subdirectories exist and have correct permissions
mkdir -p /var/www/html/writable/logs
mkdir -p /var/www/html/writable/cache
mkdir -p /var/www/html/writable/session
mkdir -p /var/www/html/writable/uploads
chown -R www-data:www-data /var/www/html/writable
chmod -R 775 /var/www/html/writable

# Run the parent image entrypoint command (Apache foreground)
echo "Starting Apache web server..."
exec apache2-foreground
