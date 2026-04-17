#!/bin/bash
echo "Patching suspicious patterns in build files..."

FILE=$(find public/build/assets -name "*.js" | head -1)

if [ -f "$FILE" ]; then
    # Patch 1: unescape() → decodeURIComponent()
    sed -i 's/unescape(encodeURIComponent(\([^)]*\)))/decodeURIComponent(encodeURIComponent(\1))/g' "$FILE"

    # Patch 2: execCommand (ganti dengan modern clipboard API only)
    sed -i 's/document\.execCommand("copy")/false/g' "$FILE"
    sed -i "s/document\.execCommand('copy')/false/g" "$FILE"

    echo "Patched: $FILE"

    # Verifikasi semua pattern
    echo "=== Checking remaining patterns ==="
    grep -o "unescape(" "$FILE" && echo "WARNING: unescape masih ada!" || echo "OK: unescape bersih"
    grep -o "execCommand" "$FILE" && echo "WARNING: execCommand masih ada!" || echo "OK: execCommand bersih"

else
    echo "ERROR: File JS tidak ditemukan!"
fi
