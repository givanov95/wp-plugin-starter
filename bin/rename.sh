#!/usr/bin/env bash
#
# Rename the starter to a real plugin. Run once after cloning.
#
#   bin/rename.sh <slug> <namespace> [<human-name>]
#
# Example:
#   bin/rename.sh acme-bookings AcmeBookings "Acme Bookings"
#
# Replaces:
#   wp-plugin-starter       -> <slug>
#   wp_plugin_starter       -> <slug-with-underscores>
#   WP_PLUGIN_STARTER       -> <SLUG_WITH_UNDERSCORES>
#   WpPluginStarter         -> <namespace>
#   WP Plugin Starter       -> <human-name>
#
set -euo pipefail

if [[ $# -lt 2 ]]; then
    echo "usage: $0 <slug> <namespace> [<human-name>]"
    echo "example: $0 acme-bookings AcmeBookings \"Acme Bookings\""
    exit 1
fi

SLUG="$1"
NAMESPACE="$2"
HUMAN_NAME="${3:-$NAMESPACE}"

SLUG_SNAKE="${SLUG//-/_}"
SLUG_UPPER=$(echo "$SLUG_SNAKE" | tr '[:lower:]' '[:upper:]')

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
cd "$ROOT"

# Files to process. Excludes vendor, node_modules, dist, .git, and this script.
FILES=$(find . \
    -type f \
    -not -path './vendor/*' \
    -not -path './node_modules/*' \
    -not -path './dist/*' \
    -not -path './.git/*' \
    -not -path './bin/rename.sh')

# Order matters: replace longer / more specific patterns first.
for FILE in $FILES; do
    sed -i \
        -e "s/WP_PLUGIN_STARTER/${SLUG_UPPER}/g" \
        -e "s/WpPluginStarter/${NAMESPACE}/g" \
        -e "s/wp_plugin_starter/${SLUG_SNAKE}/g" \
        -e "s/wp-plugin-starter/${SLUG}/g" \
        -e "s/WP Plugin Starter/${HUMAN_NAME}/g" \
        "$FILE"
done

# Rename the main plugin file to match the slug.
if [[ -f plugin.php ]]; then
    mv plugin.php "${SLUG}.php"
fi

echo "Done."
echo "Next steps:"
echo "  composer install"
echo "  npm install"
echo "  npm run build      # production"
echo "  npm run dev        # dev with HMR (creates .vite-dev flag)"
