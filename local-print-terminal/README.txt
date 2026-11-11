LOCAL PRINT TERMINAL
====================

This is a separate local PHP application. It does not modify or replace the existing POS /terminal/prints page.

INSTALLATION
1. Copy the local-print-terminal folder to the local web server document root (for example htdocs/local-print-terminal).
2. Make sure PHP extensions cURL and PDO SQLite are enabled. PHP sockets/fsockopen must be allowed.
3. Open config.php and confirm api_base_url and api_token. The generated token must match application/config/print_api.php in the online POS.
4. Ensure local-print-terminal/data is writable by the web server. The SQLite database print_terminal.sqlite is included and contains only kitchen_ip and cashier_ip settings.
5. Open http://localhost/local-print-terminal/ . Settings are at /settings.php.

ONLINE API
GET  https://arseniossilay.com/api/prints/pending
POST https://arseniossilay.com/api/prints/update-status
Both require: Authorization: Bearer <token>
POST JSON: {"prtID": 123, "prtStatus": 1}

PRINT STATUS
0 = pending, 1 = printed. A job remains pending if local printer communication fails. The agent retries it on a later poll.

SECURITY
- Keep config.php private; do not publish its token.
- The online API is HTTPS-only in the configured URL and validates the bearer token.
- Restrict access to this local application to the restaurant's LAN.
- Back up the local data/print_terminal.sqlite file to preserve printer settings.

NOTE
The local agent considers a job printed when it successfully writes the complete ESC/POS payload to the printer socket and receives no socket timeout. This cannot guarantee that paper physically came out.
