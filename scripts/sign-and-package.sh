#!/usr/bin/env bash
set -euo pipefail

usage() {
	cat <<'EOF'
Usage: NEXTCLOUD_OCC=/path/to/occ LAGER_PRIVATE_KEY=/secure/lager.key \
       LAGER_CERTIFICATE=/secure/lager.crt ./scripts/sign-and-package.sh [output-directory]
EOF
}

if [[ "${1:-}" == "--help" ]]; then
	usage
	exit 0
fi

: "${NEXTCLOUD_OCC:?Set NEXTCLOUD_OCC to the Nextcloud occ executable.}"
: "${LAGER_PRIVATE_KEY:?Set LAGER_PRIVATE_KEY to the private signing key.}"
: "${LAGER_CERTIFICATE:?Set LAGER_CERTIFICATE to the issued certificate.}"

source_dir="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
output_dir="${1:-$source_dir/dist}"

for required in git tar mktemp; do
	command -v "$required" >/dev/null || { echo "Missing required command: $required" >&2; exit 1; }
done

[[ -f "$NEXTCLOUD_OCC" ]] || { echo "occ was not found: $NEXTCLOUD_OCC" >&2; exit 1; }
[[ -f "$LAGER_PRIVATE_KEY" ]] || { echo "Private key was not found." >&2; exit 1; }
[[ -f "$LAGER_CERTIFICATE" ]] || { echo "Certificate was not found." >&2; exit 1; }
git -C "$source_dir" diff --quiet || { echo "The Git checkout has uncommitted changes." >&2; exit 1; }

version="$(sed -n 's:.*<version>\([^<]*\)</version>.*:\1:p' "$source_dir/appinfo/info.xml" | head -n 1)"
[[ -n "$version" ]] || { echo "Could not determine the app version." >&2; exit 1; }

stage_dir="$(mktemp -d)"
trap 'rm -rf "$stage_dir"' EXIT
git -C "$source_dir" archive --format=tar --prefix=lager/ HEAD | tar -xf - -C "$stage_dir"
php "$NEXTCLOUD_OCC" integrity:sign-app \
	--privateKey="$LAGER_PRIVATE_KEY" \
	--certificate="$LAGER_CERTIFICATE" \
	--path="$stage_dir/lager"

mkdir -p "$output_dir"
archive="$output_dir/lager-$version.tar.gz"
tar -C "$stage_dir" -czf "$archive" lager

if command -v sha256sum >/dev/null; then
	sha256sum "$archive" | tee "$archive.sha256"
else
	shasum -a 256 "$archive" | tee "$archive.sha256"
fi
