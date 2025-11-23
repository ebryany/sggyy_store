@echo off
REM Script untuk push project ke GitHub (Windows)
REM Usage: update_project.bat atau !update_project

setlocal enabledelayedexpansion

set REPO_URL=https://github.com/ebryany/sggyy_store.git
set BRANCH=main

echo 🚀 Starting Git Push Process...
echo.

REM Check if git is initialized
if not exist ".git" (
    echo ⚠️  Git not initialized. Initializing...
    git init
    git branch -M main
    
    REM Check if remote exists
    git remote get-url origin >nul 2>&1
    if errorlevel 1 (
        echo 📦 Adding remote origin...
        git remote add origin %REPO_URL%
    )
)

REM Check if remote exists
git remote get-url origin >nul 2>&1
if errorlevel 1 (
    echo 📦 Adding remote origin...
    git remote add origin %REPO_URL%
)

REM Check current branch
for /f "tokens=*" %%i in ('git branch --show-current') do set CURRENT_BRANCH=%%i
if not "%CURRENT_BRANCH%"=="%BRANCH%" (
    echo 🔄 Switching to branch: %BRANCH%
    git checkout -b %BRANCH% 2>nul || git checkout %BRANCH%
)

REM Check for changes
git status --porcelain >nul 2>&1
if errorlevel 1 (
    echo ✅ No changes to commit. Repository is up to date!
    exit /b 0
)

REM Show status
echo 📊 Current status:
git status --short

REM Add all changes
echo.
echo ➕ Adding all changes...
git add .

REM Get commit message from argument or use default
if "%1"=="" (
    for /f "tokens=*" %%i in ('powershell -Command "Get-Date -Format \"yyyy-MM-dd HH:mm:ss\""') do set TIMESTAMP=%%i
    set COMMIT_MSG=Update project - !TIMESTAMP!
) else (
    set COMMIT_MSG=%*
)

REM Commit changes
echo 💾 Committing changes...
echo    Message: %COMMIT_MSG%
git commit -m "%COMMIT_MSG%"

REM Push to remote
echo.
echo 📤 Pushing to GitHub...
echo    Repository: %REPO_URL%
echo    Branch: %BRANCH%

REM Try to push, if fails, set upstream
git push -u origin %BRANCH% >nul 2>&1
if errorlevel 1 (
    echo ⚠️  Setting upstream branch...
    git push --set-upstream origin %BRANCH%
) else (
    git push
)

echo.
echo ✅ Successfully pushed to GitHub!
echo    Repository: %REPO_URL%
echo    Branch: %BRANCH%
echo.

