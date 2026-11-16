#!/usr/bin/env bash
#
# Run on the UK server (WHM Terminal) from the app folder:
#
#     cd /home/naeeupql/naeemfoundation && bash deploy/export-content-for-canada.sh
#
# Makes two files in ./ca-export/ to load into the Canadian site:
#   ca-content.sql        admin-managed content only (hero slides, appeals, causes,
#                         projects, products, orphans, videos, news, reports...),
#                         marked as Canadian content (region CA).
#   ca-content-files.tar.gz  images/reports uploaded through the admin (not in git).
#
# NO personal data is exported: no donations, orders, donors, registrations,
# messages, volunteers, applications, questions, users or sessions.
set -euo pipefail

cd "$(dirname "$0")/.."

env_get() { grep -E "^$1=" .env | head -1 | cut -d= -f2- | sed -e 's/^"//' -e 's/"$//'; }

DB_NAME="$(env_get DB_DATABASE)"
DB_USER="$(env_get DB_USERNAME)"
DB_PASS="$(env_get DB_PASSWORD)"
DB_HOST="$(env_get DB_HOST)"; DB_HOST="${DB_HOST:-localhost}"

# Content tables (the Laravel migrations create all of these).
TABLES="hero_slides appeals causes projects products orphans hajj_videos hajj_step_videos page_videos page_heroes community_videos annual_reports news_posts"
# Of those, the ones owned by a region (carry a `region` column).
REGION_TABLES="hero_slides appeals causes projects products orphans hajj_videos"

OUT="$(pwd)/ca-export"
mkdir -p "$OUT"

echo "== Database: $DB_NAME"
# Only tables that exist on this server.
EXISTING=""
for t in $TABLES; do
    if MYSQL_PWD="$DB_PASS" mysql -h "$DB_HOST" -u "$DB_USER" -N -e "SHOW TABLES LIKE '$t'" "$DB_NAME" | grep -qx "$t"; then
        EXISTING="$EXISTING $t"
    fi
done
echo "Tables:$EXISTING"

{
    echo "SET FOREIGN_KEY_CHECKS=0;"
    MYSQL_PWD="$DB_PASS" mysqldump -h "$DB_HOST" -u "$DB_USER" \
        --no-create-info --skip-triggers --complete-insert --replace "$DB_NAME" $EXISTING
    for t in $REGION_TABLES; do
        case " $EXISTING " in *" $t "*) echo "UPDATE \`$t\` SET region='CA';" ;; esac
    done
    echo "SET FOREIGN_KEY_CHECKS=1;"
} > "$OUT/ca-content.sql"

echo "== Files uploaded through the admin"
LIST="$OUT/files.list"
{
    git ls-files --others --exclude-standard public/images public/reports public/pdf 2>/dev/null || true
    [ -d storage/app/public ] && find storage/app/public -type f ! -name .gitignore 2>/dev/null || true
} > "$LIST"
echo "$(wc -l < "$LIST") files"
tar -czf "$OUT/ca-content-files.tar.gz" -T "$LIST" 2>/dev/null || tar -czf "$OUT/ca-content-files.tar.gz" --files-from=/dev/null

rm -f "$LIST"
# So the cPanel File Manager of the app's account can download them.
chown -R "$(stat -c %U .)":"$(stat -c %G .)" "$OUT" 2>/dev/null || true

echo
ls -lh "$OUT"
echo
echo "Download both files, then follow the steps for loading them into the Canadian site."
