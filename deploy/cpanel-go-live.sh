#!/usr/bin/env bash
#
# Points the domain's web folder (public_html) at this app's public/ folder.
# The current public_html (e.g. the old WordPress site) is NOT deleted: it is
# renamed to public_html_wordpress_backup so it can be restored.
#
#     cd ~/naeemfoundation-ca && bash deploy/cpanel-go-live.sh
set -euo pipefail

APP_DIR="$(cd "$(dirname "$0")/.." && pwd)"
WEB_DIR="$HOME/public_html"
BACKUP_DIR="$HOME/public_html_wordpress_backup"

if [ ! -f "$APP_DIR/public/index.php" ]; then
    echo "!! $APP_DIR/public/index.php not found - run this from the app folder."
    exit 1
fi

if [ -L "$WEB_DIR" ]; then
    echo "public_html is already a link to: $(readlink "$WEB_DIR")"
    exit 0
fi

if [ -e "$BACKUP_DIR" ]; then
    echo "!! $BACKUP_DIR already exists - move it away first so nothing is overwritten."
    exit 1
fi

echo "This will:"
echo "  1. rename  $WEB_DIR  ->  $BACKUP_DIR   (old site kept)"
echo "  2. link    $WEB_DIR  ->  $APP_DIR/public"
read -r -p "Type YES to continue: " ANSWER
[ "$ANSWER" = "YES" ] || { echo "Cancelled."; exit 1; }

mv "$WEB_DIR" "$BACKUP_DIR"
ln -s "$APP_DIR/public" "$WEB_DIR"

echo "Done. public_html now serves the new site."
echo "To undo: rm $WEB_DIR && mv $BACKUP_DIR $WEB_DIR"
