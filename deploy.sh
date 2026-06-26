#!/bin/bash
# Deploy from anywhere (including GitHub Codespaces).
#
# This script only builds assets and pushes to GitHub. The actual server
# deployment (rsync + git pull + migrate + seed + cache clear) is handled
# automatically by GitHub Actions (.github/workflows/deploy.yml), because
# Codespaces cannot reach the server over SSH directly.
set -e

echo "Building assets..."
npm run build

echo "Committing and pushing to GitHub..."
git add .
git commit -m "Update $(date '+%Y-%m-%d %H:%M')" || echo "Nothing new to commit — pushing anyway."
git push origin main

echo ""
echo "Pushed. GitHub Actions is now deploying to the server."
echo "Watch progress: https://github.com/coadingmasters/naeemfoundationuk/actions"
echo "Site: https://naeemfoundation.qubexistech.com"
