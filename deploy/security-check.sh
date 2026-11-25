#!/bin/bash
# Malware scan + clean-up for the UK server (run as root in WHM Terminal):
#
#   bash /home/naeeupql/naeemfoundation/deploy/security-check.sh
#
# 1. SCAN   - read-only; prints everything suspicious it finds.
# 2. CLEAN  - MOVES (never deletes) suspicious files to /root/quarantine-<date>,
#             puts back every tracked file that was changed on the server,
#             re-installs vendor/ from composer.lock, clears the caches.
# 3. VERIFY - fetches the pages from outside to confirm the spam is gone.
#
# The whole output is also saved to /root/security-run-<date>.log

APP=/home/naeeupql/naeemfoundation
ACCOUNT=naeeupql
HOME_DIR=/home/$ACCOUNT
PHP=/opt/cpanel/ea-php84/root/usr/bin/php
STAMP=$(date +%F-%H%M)
Q=/root/quarantine-$STAMP
LOG=/root/security-run-$STAMP.log
SPAM='MBG11|SAKTI33|Portal Hiburan|Hiburan Digital|postimg\.cc/02FQQzFR'
SHELL_SIG='eval[[:space:]]*\([[:space:]]*(base64_decode|gzinflate|gzuncompress|str_rot13)|(assert|eval|system|shell_exec|passthru|popen|proc_open)[[:space:]]*\([[:space:]]*\$_(GET|POST|REQUEST|COOKIE|SERVER)|base64_decode\([[:space:]]*.[A-Za-z0-9+/=]{300,}|create_function[[:space:]]*\(|FilesMan|c99shell|r57shell|WSO[[:space:]]*[0-9]'
EXCL=(--exclude=security-check.sh --exclude=UploadExtensionTest.php --exclude-dir=node_modules --exclude-dir=.git --exclude-dir=mail --exclude-dir=logs --exclude-dir=.cpanel --exclude-dir=tmp)

exec > >(tee "$LOG") 2>&1
cd "$APP" || { echo "App folder not found: $APP"; exit 1; }
sec() { echo; echo "================ $* ================"; }

sec "0. Spam as seen from outside (before)"
for p in /sadaqah /about /zakat /; do
  printf '%-10s ' "$p"; curl -s -m 20 "https://naeemfoundation.co.uk$p" | grep -oiE '<title>[^<]*' | head -1
done

sec "1. Tracked files changed on the server (git)"
git status --short | head -30
echo "--- untracked / ignored (except vendor, node_modules, storage, .env):"
git status --short --ignored | grep -vE '^!! (vendor/|node_modules/|storage/|\.env$|bootstrap/cache/)' | grep '^??' | head -30

sec "2. vendor/autoload.php and composer loader files"
ls -la --time-style=full-iso vendor/autoload.php vendor/composer/autoload_real.php public/index.php public/.htaccess public/.user.ini 2>&1
echo "--- suspicious words inside the composer loader:"
grep -nE 'eval|base64|gzinflate|file_get_contents\(.http|curl_|file_put_contents|REQUEST_URI|HTTP_USER_AGENT|https?://' vendor/autoload.php vendor/composer/autoload_*.php vendor/composer/ClassLoader.php 2>/dev/null | grep -v 'github.com\|getcomposer' | head -10
echo "--- vendor PHP files changed AFTER the last composer install:"
find vendor -type f -name '*.php' -newer vendor/composer/installed.json 2>/dev/null | head -15

sec "3. Spam text anywhere in the account (files)"
grep -rIlE "$SPAM" "$HOME_DIR" "${EXCL[@]}" 2>/dev/null | head -20

sec "4. Web-shell patterns in PHP files"
grep -rIlE "$SHELL_SIG" "$HOME_DIR" --include='*.php' --include='*.phtml' --include='*.phar' --include='*.inc' "${EXCL[@]}" 2>/dev/null | head -30

sec "5. PHP files where none should be (public, storage, uploads)"
find public storage -type f \( -name '*.php' -o -name '*.phtml' -o -name '*.phar' -o -name '*.php[0-9]' -o -name '*.inc' \) ! -path 'public/index.php' ! -path 'storage/framework/views/*' 2>/dev/null | head -30
echo "--- images/videos/uploads that contain PHP code:"
grep -rla '<?php' public/images public/videos public/uploads storage/app/public 2>/dev/null | head -20

sec "6. Recently changed code files (last 5 days, outside vendor/build/caches)"
find "$HOME_DIR" -xdev -type f \( -name '*.php' -o -name '.htaccess' -o -name '.user.ini' -o -name '*.phtml' -o -name '*.html' -o -name '*.inc' \) -mtime -5 \
  -not -path '*/node_modules/*' -not -path '*/.git/*' -not -path '*/storage/framework/*' -not -path '*/storage/logs/*' -not -path '*/vendor/*' -not -path '*/public/build/*' -not -path '*/mail/*' 2>/dev/null | head -40

sec "7. Dangerous server directives (.htaccess / .user.ini)"
grep -rnE 'auto_prepend_file|auto_append_file|RewriteRule[^#]*https?://|php_value|php_flag|SetHandler|AddHandler|AddType' "$HOME_DIR" --include='.htaccess' --include='.user.ini' "${EXCL[@]}" 2>/dev/null | head -20

sec "8. Scheduled jobs and running processes of the account"
crontab -l -u "$ACCOUNT" 2>&1 | head -20
ps -u "$ACCOUNT" -o pid,etime,args 2>/dev/null | grep -v 'ps -u\|grep' | head -15

sec "9. Web log: PHP files requested other than index.php, and odd POSTs (last lines)"
DL=/var/log/apache2/domlogs/naeemfoundation.co.uk
[ -f "$DL" ] || DL=$(ls /var/log/apache2/domlogs/naeemfoundation.co.uk* 2>/dev/null | head -1)
if [ -f "$DL" ]; then
  grep -E '\.php' "$DL" | grep -vE 'GET /index\.php' | tail -8
  echo "--- POST to unusual paths:"
  grep -E '"POST ' "$DL" | grep -vE 'donate|paypal|contact|login|volunteer|newsletter|admin|hajj|cart|order|career|mufti|enquir|webhook|logout|address|sponsor|shop|checkout' | tail -8
else
  echo "(domain log not found)"
fi

# ------------------------------------------------------------------ CLEAN
sec "10. CLEAN-UP (nothing is deleted: moved to $Q)"
mkdir -p "$Q"
mv_safe() { # mv_safe <file>  keeps the folder structure inside the quarantine
  [ -e "$1" ] || return
  mkdir -p "$Q$(dirname "$1")" && mv "$1" "$Q$(dirname "$1")/" && echo "moved: $1"
}

# a) PHP code in places that must not have any
find public storage -type f \( -name '*.php' -o -name '*.phtml' -o -name '*.phar' -o -name '*.php[0-9]' -o -name '*.inc' \) ! -path 'public/index.php' ! -path 'storage/framework/views/*' 2>/dev/null | while read -r f; do mv_safe "$PWD/$f"; done
grep -rla '<?php' public/images public/videos public/uploads storage/app/public 2>/dev/null | while read -r f; do mv_safe "$PWD/$f"; done

# b) any file carrying the spam text (not logs, not git)
grep -rIlE "$SPAM" "$HOME_DIR" "${EXCL[@]}" 2>/dev/null | while read -r f; do mv_safe "$f"; done

# c) web-shell patterns outside vendor/ (vendor is replaced as a whole below)
grep -rIlE "$SHELL_SIG" "$HOME_DIR" --include='*.php' --include='*.phtml' --include='*.phar' --include='*.inc' "${EXCL[@]}" 2>/dev/null | grep -v "^$APP/vendor/" | while read -r f; do mv_safe "$f"; done

# d) tracked files that differ from git (except .htaccess, which cPanel edits on purpose)
git diff --name-only | grep -v '^public/\.htaccess$' | while read -r f; do cp --parents "$f" "$Q/" 2>/dev/null; git checkout -- "$f" && echo "restored from git: $f"; done
git diff --quiet -- public/.htaccess || { echo "NOTE: public/.htaccess differs from git (cPanel handler block is normal). Differences:"; git diff -- public/.htaccess | head -20; }

# e) untracked PHP files outside vendor/storage
git ls-files --others --exclude-standard -- '*.php' | while read -r f; do mv_safe "$PWD/$f"; done

# f) vendor/: replace by a clean install from composer.lock
if [ -d vendor ]; then mkdir -p "$Q$APP" && mv vendor "$Q$APP/vendor" && echo "moved: old vendor/"; fi
COMPOSER_ALLOW_SUPERUSER=1 "$PHP" /usr/local/bin/composer install --no-dev --optimize-autoloader --no-interaction 2>&1 | tail -4

# g) compiled/cached files are rebuilt automatically
rm -f storage/framework/views/*.php 2>/dev/null
"$PHP" artisan optimize:clear 2>&1 | tail -3
chown -R "$ACCOUNT:$ACCOUNT" vendor storage bootstrap/cache 2>/dev/null

sec "11. VERIFY (from outside)"
sleep 2
for p in /sadaqah /about /zakat /; do
  printf '%-10s ' "$p"; curl -s -m 20 "https://naeemfoundation.co.uk$p" | grep -oiE '<title>[^<]*' | head -1
done
echo; echo "Quarantine folder: $Q  ($(find "$Q" -type f 2>/dev/null | wc -l) files)"
echo "Full output saved in: $LOG"
echo "=== DONE ==="
