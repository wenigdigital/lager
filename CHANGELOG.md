# Changelog

## 0.2.1

- Fix CSRF compatibility for read-only requests and the app page on Nextcloud 30.

## 0.2.0

- Restrict deleting locations, cabinets, slots, and articles to Nextcloud administrators.
- Protect state-changing requests with Nextcloud's CSRF protection.
- Make cascading deletions transactional so an incomplete deletion is rolled back.
- Make stock movements atomic to prevent concurrent withdrawals from being recorded twice.
- Improve validation of duplicate article names and EAN/QR codes.
- Prepare metadata and repository information for the Nextcloud App Store.

## 0.1.0

- Initial public release.
