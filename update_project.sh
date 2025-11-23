#!/bin/bash

# Script untuk push project ke GitHub
# Usage: ./update_project.sh atau !update_project

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Repository info
REPO_URL="https://github.com/ebryany/sggyy_store.git"
BRANCH="main"

echo -e "${BLUE}🚀 Starting Git Push Process...${NC}\n"

# Check if git is initialized
if [ ! -d ".git" ]; then
    echo -e "${YELLOW}⚠️  Git not initialized. Initializing...${NC}"
    git init
    git branch -M main
    
    # Check if remote exists
    if ! git remote get-url origin &>/dev/null; then
        echo -e "${YELLOW}📦 Adding remote origin...${NC}"
        git remote add origin "$REPO_URL"
    fi
fi

# Check if remote exists
if ! git remote get-url origin &>/dev/null; then
    echo -e "${YELLOW}📦 Adding remote origin...${NC}"
    git remote add origin "$REPO_URL"
fi

# Check current branch
CURRENT_BRANCH=$(git branch --show-current)
if [ "$CURRENT_BRANCH" != "$BRANCH" ]; then
    echo -e "${YELLOW}🔄 Switching to branch: $BRANCH${NC}"
    git checkout -b "$BRANCH" 2>/dev/null || git checkout "$BRANCH"
fi

# Check for changes
if [ -z "$(git status --porcelain)" ]; then
    echo -e "${GREEN}✅ No changes to commit. Repository is up to date!${NC}"
    exit 0
fi

# Show status
echo -e "${BLUE}📊 Current status:${NC}"
git status --short

# Add all changes
echo -e "\n${BLUE}➕ Adding all changes...${NC}"
git add .

# Get commit message from argument or use default
COMMIT_MSG="${1:-Update project - $(date '+%Y-%m-%d %H:%M:%S')}"

# Commit changes
echo -e "${BLUE}💾 Committing changes...${NC}"
echo -e "${YELLOW}   Message: $COMMIT_MSG${NC}"
git commit -m "$COMMIT_MSG"

# Push to remote
echo -e "\n${BLUE}📤 Pushing to GitHub...${NC}"
echo -e "${YELLOW}   Repository: $REPO_URL${NC}"
echo -e "${YELLOW}   Branch: $BRANCH${NC}"

# Try to push, if fails, set upstream
if ! git push -u origin "$BRANCH" 2>/dev/null; then
    echo -e "${YELLOW}⚠️  Setting upstream branch...${NC}"
    git push --set-upstream origin "$BRANCH"
else
    git push
fi

echo -e "\n${GREEN}✅ Successfully pushed to GitHub!${NC}"
echo -e "${GREEN}   Repository: $REPO_URL${NC}"
echo -e "${GREEN}   Branch: $BRANCH${NC}\n"

