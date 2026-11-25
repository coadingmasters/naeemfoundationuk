#!/bin/bash
# READ-ONLY. Finds out HOW the attacker got in (run after security-check.sh):
#   cd /home/naeeupql/naeemfoundation && git fetch origin main && git show origin/main:deploy/security-forensics.sh > /root/forensics.sh && bash /root/forensics.sh
# Nothing is changed or deleted. Output is also saved to /root/forensics-<date>.log

APP=/home/naeeupql/naeemfoundation
ACCOUNT=naeeupql
HOME_DIR=/home/$ACCOUNT
LOG=/root/forensics-$(date +%F-%H%M).log
exec > >(tee "$LOG") 2>&1
cd "$APP" || exit 1
sec() { echo; echo "================ $* ================"; }
Q=$(ls -d /root/quarantine-* 2>/dev/null | tail -1)
DL=/var/log/apache2/domlogs/naeemfoundation.co.uk
[ -f "$DL" ] || DL=$(ls /var/log/apache2/domlogs/naeemfoundation.co.uk* 2>/dev/null | head -1)

sec "1. The infected public/index.php (kept in $Q)"
if [ -f "$Q/public/index.php" ]; then
  ls -la --time-style=full-iso "$Q/public/index.php" public/index.php
  echo "--- lines that differ from the real file (cut to 200 chars):"
  diff "$Q/public/index.php" public/index.php | grep '^<' | cut -c1-200 | head -25
  echo "--- web addresses inside the infected file:"
  grep -oE "https?://[^\"' )<>]+" "$Q/public/index.php" | sort -u | grep -v 'laravel.com' | head -10
  echo "--- risky calls inside it:"
  grep -noE 'eval|base64_decode|gzinflate|file_put_contents|curl_exec|file_get_contents|shell_exec|system\(|passthru|REQUEST_URI|HTTP_USER_AGENT' "$Q/public/index.php" | sort -u -t: -k2 | head -12
else
  echo "(infected copy not found in quarantine)"
fi

sec "2. Everything else touched on the account at the same time (01:45-02:05 on 25 Nov)"
find "$HOME_DIR" -xdev -type f -newermt "2026-11-25 01:45:00" ! -newermt "2026-11-25 02:05:00" \
  -not -path '*/.git/*' -not -path '*/storage/framework/*' -not -path '*/storage/logs/*' -not -path '*/tmp/*' -not -path '*/mail/*' 2>/dev/null | head -20

sec "3. Web requests around that time (static files hidden)"
if [ -f "$DL" ]; then
  grep -E '25/Nov/2026:01:(4[5-9]|5[0-9])|25/Nov/2026:02:0[0-4]' "$DL" | grep -vE '\.(css|js|jpe?g|png|webp|svg|gif|woff2?|ico|mp4) ' | cut -c1-230 | head -40
else
  echo "(domain log not found)"
fi

sec "4. public/sitemap.xml and the Google verification file (untracked)"
ls -la --time-style=full-iso public/sitemap.xml public/googled2379b0b29500e5d.html 2>&1
echo "--- addresses in sitemap that are NOT naeemfoundation.co.uk:"
grep -o '<loc>[^<]*' public/sitemap.xml 2>/dev/null | grep -v 'naeemfoundation.co.uk' | head -10
echo "--- sitemap entries: $(grep -c '<loc>' public/sitemap.xml 2>/dev/null)"
echo "--- google file:"; head -c 300 public/googled2379b0b29500e5d.html 2>/dev/null; echo

sec "5. Logins: cPanel, SSH, FTP (most recent first)"
echo "--- cPanel/WHM logins for this account:"
grep -E "$ACCOUNT" /usr/local/cpanel/logs/login_log 2>/dev/null | tail -12 | cut -c1-200
echo "--- SSH logins:"
grep -E 'Accepted|session opened' /var/log/secure 2>/dev/null | tail -10 | cut -c1-200
last -n 10 2>/dev/null | head -10
echo "--- SSH keys allowed for the account:"
cat "$HOME_DIR/.ssh/authorized_keys" 2>/dev/null | cut -c1-90
echo "--- FTP accounts:"
uapi --user="$ACCOUNT" Ftp list_ftp 2>/dev/null | grep -E '^\s+(user|homedir|dir):' | head -20
grep -E "$ACCOUNT" /var/log/messages 2>/dev/null | grep -i 'pure-ftpd\|proftpd' | tail -6 | cut -c1-200

sec "6. What is hosted on this account"
ls -la "$HOME_DIR" | head -30
ls /var/cpanel/userdata/$ACCOUNT 2>/dev/null | head

sec "7. Does the live .htaccess block PHP in uploads (the fix from last week)?"
grep -n 'phtml\|php\[0-9\]\|index\\.php' public/.htaccess | head -5
git log -1 --format='server code is at commit: %h %s' | cut -c1-120

echo; echo "Full output saved in: $LOG"
echo "=== DONE ==="
