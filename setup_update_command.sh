#!/bin/bash

# Script untuk setup command !update_project
# Run this once: bash setup_update_command.sh

set -e

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
UPDATE_SCRIPT="$SCRIPT_DIR/update_project.sh"

echo "🔧 Setting up !update_project command..."

# Make script executable
chmod +x "$UPDATE_SCRIPT"

# Detect shell
if [ -n "$ZSH_VERSION" ]; then
    SHELL_RC="$HOME/.zshrc"
    SHELL_NAME="zsh"
elif [ -n "$BASH_VERSION" ]; then
    SHELL_RC="$HOME/.bashrc"
    SHELL_NAME="bash"
else
    SHELL_RC="$HOME/.profile"
    SHELL_NAME="sh"
fi

# Create alias
ALIAS_LINE="alias !update_project='bash $UPDATE_SCRIPT'"

# Check if alias already exists
if grep -q "!update_project" "$SHELL_RC" 2>/dev/null; then
    echo "⚠️  Alias already exists in $SHELL_RC"
    echo "   Updating..."
    # Remove old alias
    sed -i.bak "/alias !update_project=/d" "$SHELL_RC"
fi

# Add alias
echo "" >> "$SHELL_RC"
echo "# Auto-generated alias for update_project.sh" >> "$SHELL_RC"
echo "$ALIAS_LINE" >> "$SHELL_RC"

echo "✅ Alias added to $SHELL_RC"
echo ""
echo "📝 To use the command, run:"
echo "   source $SHELL_RC"
echo "   !update_project"
echo ""
echo "   Or restart your terminal"
echo ""
echo "💡 You can also use with custom commit message:"
echo "   !update_project 'Your custom commit message'"

