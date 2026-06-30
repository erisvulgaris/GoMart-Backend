#!/bin/bash
set -e

echo "=== GoMart Startup Script ==="

# Wait for MySQL database container to start
echo "Waiting for MySQL database at host 'db' to become ready..."
until mysql -h"db" -u"$MYSQL_USER" -p"$MYSQL_PASSWORD" -e "SELECT 1"; do
    echo "MySQL is unavailable - sleeping..."
    sleep 2
done
echo "MySQL is up and online!"

# Check if the database has been seeded
# We do this by checking if the 'settings' table exists
DB_EXISTS=$(mysql -h"db" -u"$MYSQL_USER" -p"$MYSQL_PASSWORD" -D"$MYSQL_DATABASE" -e "SHOW TABLES LIKE 'settings';" | grep settings || true)

if [ -z "$DB_EXISTS" ]; then
    echo "Database is empty! Seeding 'database.sql' now..."
    mysql -h"db" -u"$MYSQL_USER" -p"$MYSQL_PASSWORD" "$MYSQL_DATABASE" < /var/www/html/database/database.sql
    echo "Database seeding completed successfully!"
else
    echo "Database already seeded. Skipping SQL import."
fi

# Run the parent image entrypoint command (Apache foreground)
echo "Starting Apache web server..."
exec apache2-foreground
