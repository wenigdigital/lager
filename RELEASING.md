# Store release

The release package is built from a clean Git checkout and signed in a temporary
directory. The private key and the certificate are never copied into this
repository or into the release archive.

## Prerequisites

- The certificate request for app ID `lager` has been approved and its certificate
  is available locally as `lager.crt`.
- A local Nextcloud installation provides the matching `occ` command.
- The checkout is clean and checked out at the exact release commit.

## Build the signed archive

Run the following on the machine that has Nextcloud's `occ` command available:

```sh
NEXTCLOUD_OCC=/var/www/nextcloud/occ \
LAGER_PRIVATE_KEY=/secure/path/lager-nextcloud-app.key \
LAGER_CERTIFICATE=/secure/path/lager.crt \
./scripts/sign-and-package.sh ./dist
```

The script creates `dist/lager-<version>.tar.gz`. It signs the temporary copy
that is archived, leaving the Git checkout unchanged.

## Publish

1. Install the archive on a test Nextcloud instance and exercise login, stock
   movement, deletion as an administrator, and deletion denial as a regular user.
2. Upload the generated archive for version `0.2.0` to the Nextcloud App Store.
3. Create the matching GitHub release and attach the same archive and its SHA-256
   checksum.

Do not commit, upload, or share the private key.
