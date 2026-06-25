#!/bin/bash
echo "Building assets..."
npm run build
echo "Pushing to GitHub..."
git add .
git commit -m "Update $(date '+%Y-%m-%d %H:%M')"
git push origin main
echo "Deploying to server..."
scp -P 65002 -r public/build public/images u721035936@145.79.26.12:/home/u721035936/domains/naeemfoundation.qubexistech.com/public_html/
ssh -p 65002 u721035936@145.79.26.12 "cd ~/domains/naeemfoundation.qubexistech.com/naeemfoundation && git pull origin main && php artisan view:clear && php artisan cache:clear"
echo "Done! Site is live."
