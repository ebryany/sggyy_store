#!/bin/bash
# Script untuk pull di server dengan handle local changes

echo "📦 Checking git status..."
git status

echo ""
echo "🔄 Stashing local changes..."
git stash push -m "Local changes before pull - $(date '+%Y-%m-%d %H:%M:%S')"

echo ""
echo "📥 Pulling latest code..."
git pull origin main

echo ""
echo "📋 Checking if there are stashed changes..."
if git stash list | grep -q "stash@{0}"; then
    echo "⚠️  There are stashed changes. Review them with: git stash show"
    echo "To apply stashed changes: git stash pop"
    echo "To discard stashed changes: git stash drop"
else
    echo "✅ No stashed changes"
fi

echo ""
echo "✅ Done!"

