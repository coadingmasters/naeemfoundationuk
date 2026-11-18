#!/usr/bin/env bash
#
# One-time setup of this app on a cPanel shared host (e.g. Namecheap Stellar),
# run from cPanel -> Terminal inside the cloned repository folder:
#
#     cd ~/naeemfoundation-ca && bash deploy/cpanel-setup.sh
#
# It installs the PHP packages, writes .env (asks for the database details),
# creates the app key and tables, and links storage. It does NOT touch public_html:
# that is a separate, deliberate step (deploy/cpanel-go-live.sh).
set -euo pipefail

cd "$(dirname "$0")/.."

SITE_URL="${SITE_URL:-https://naeemfoundation.ca}"

echo "== PHP"
php -v | head -1
if ! php -r 'exit(version_compare(PHP_VERSION, "8.3.0", ">=") ? 0 : 1);'; then
    echo "!! PHP 8.3 or newer is needed. In cPanel open 'Select PHP Version', choose 8.4, save, then run this again."
    exit 1
fi

echo "== Composer"
if command -v composer >/dev/null 2>&1; then
    COMPOSER="composer"
elif [ -x /opt/cpanel/composer/bin/composer ]; then
    COMPOSER="/opt/cpanel/composer/bin/composer"
else
    echo "Composer not found - downloading it into this folder (official getcomposer.org build)."
    curl -sS https://getcomposer.org/download/latest-stable/composer.phar -o composer.phar
    COMPOSER="php composer.phar"
fi
$COMPOSER install --no-dev --optimize-autoloader --no-interaction

echo "== .env"
if [ -f .env ]; then
    echo ".env already exists - leaving it as it is."
else
    read -r -p "Database name (e.g. naeeupql_ca): " DB_NAME
    read -r -p "Database user: " DB_USER
    read -r -s -p "Database password (hidden): " DB_PASS
    echo
    cat > .env <<ENV
APP_NAME="Naeem Foundation"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=${SITE_URL}
APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_MAINTENANCE_DRIVER=file
BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=${DB_NAME}
DB_USERNAME=${DB_USER}
DB_PASSWORD="${DB_PASS}"
# This install is the Canadian site: its admin-managed content belongs to Canada.
CONTENT_REGION=CA
# Some shared hosts default to MyISAM, which can't hold Laravel's indexes.
DB_ENGINE=InnoDB

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null
BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database
CACHE_STORE=database

# Emails: set a real mailer before taking donations (receipts are emailed).
MAIL_MAILER=log
MAIL_FROM_ADDRESS="contact@naeemfoundation.ca"
MAIL_FROM_NAME="Naeem Foundation"
MAIL_NOTIFY_ADDRESS="contact@naeemfoundation.ca"

PAYPAL_MODE=sandbox
PAYPAL_BRAND_NAME="Naeem Foundation"
PAYPAL_SANDBOX_CLIENT_ID=
PAYPAL_SANDBOX_CLIENT_SECRET=
PAYPAL_SANDBOX_WEBHOOK_ID=
PAYPAL_LIVE_CLIENT_ID=
PAYPAL_LIVE_CLIENT_SECRET=
PAYPAL_LIVE_WEBHOOK_ID=

# Jamia Binoria sponsorship API: the student/orphan list. Copy the key from the UK server's .env.
SPONSOR_API_URL=https://binoria.org/eNazimOnline
SPONSOR_API_KEY=

CRM_ENABLED=false
CRM_RECURRING=false
ENV
    chmod 600 .env
fi

echo "== App key, tables, storage"
grep -q '^APP_KEY=.\+' .env || php artisan key:generate --force
php artisan migrate --force
php artisan storage:link || true
chmod -R ug+rwX storage bootstrap/cache
php artisan config:clear
php artisan view:clear

echo
echo "Done. The app is installed in: $(pwd)"
echo "Next: bash deploy/cpanel-go-live.sh   (points public_html at this app)"
