# Cara Menggunakan Git Update Script

## Quick Start

### Windows (PowerShell/Git Bash)
```bash
# Jalankan script
bash update_project.sh

# Atau dengan custom commit message
bash update_project.sh "Update: Menambahkan fitur baru"
```

### Linux/Mac
```bash
# Setup alias (hanya sekali)
bash setup_update_command.sh

# Reload shell config
source ~/.bashrc  # atau source ~/.zshrc

# Gunakan command
!update_project

# Atau dengan custom commit message
!update_project "Update: Menambahkan fitur baru"
```

## Manual Git Commands

Jika ingin manual:

```bash
git add .
git commit -m "Your commit message"
git push origin main
```

## Script Files

- `update_project.sh` - Script untuk Linux/Mac/Git Bash
- `update_project.bat` - Script untuk Windows CMD
- `setup_update_command.sh` - Setup alias `!update_project` (Linux/Mac)

## Repository Info

- **URL**: https://github.com/ebryany/sggyy_store.git
- **Branch**: main

