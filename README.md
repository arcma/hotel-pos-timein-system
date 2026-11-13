# Arsenio's Silay POS

A web-based Point of Sale (POS) and restaurant operations system built with PHP and CodeIgniter. The project includes POS order management, table and kitchen workflows, category-based kitchen printer routing, CMS pages/navigation/footer management, and HRIS calendar functionality.

> **Important:** Review the environment-specific configuration before deploying. Do not commit production database credentials, printer API tokens, or other secrets.

## Requirements

For a standard PHP deployment:

- PHP version compatible with the project (the POS has been used with PHP 8.1).
- MySQL with the `mysqli` PHP extension.
- Apache or another web server configured to serve the project and support its `.htaccess` rewrite rules.
- PHP extensions required by the application and its installed libraries.

For the included Docker development setup:

- Docker Desktop or Docker Engine
- Docker Compose

## Project structure

```text
.
├── application/       # CodeIgniter application: controllers, models, views, config
├── assets/            # Front-end assets
├── database/          # Database schema and migration SQL files
├── docker/            # Docker build configuration
├── local-print-terminal/ # Separate local PHP print-terminal application
├── system/            # CodeIgniter framework files
├── index.php          # Application entry point
├── docker-compose.yml # Local Docker services
├── arseniossilay.sql  # Database SQL dump
└── README.md
```

## Local development with Docker

1. Clone or copy this repository.
2. Review `docker-compose.yml` and `application/config/database.php`. The included configuration is intended for the supplied local Docker setup and must be reviewed before use in another environment.
3. Start the services from the project root:

   ```bash
   docker compose up -d --build
   ```

4. Open the application:
   - POS application: <http://localhost:8000>
   - phpMyAdmin: <http://localhost:8080>
   - MySQL is exposed on host port `3307` (inside the Docker network it is available as `mysql:3306`).

5. Import the database dump into the `arseniossilay` database using phpMyAdmin or the MySQL command line. The supplied dump is `arseniossilay.sql`.

To stop the services:

```bash
docker compose down
```

To stop the services and also remove the Docker database volume (this permanently deletes the local database data):

```bash
docker compose down -v
```

The Compose file defines PHP, MySQL 8.0, and phpMyAdmin services. Its sample database credentials are for local development only; replace them before using any shared or production environment.

## Database setup and updates

- Main SQL dump: `arseniossilay.sql`
- Additional schema and migration scripts: `database/`
- Recent printer settings migration: `database/migrations/20261113_add_bar_pantry_printer_settings.sql`

For an existing installation, back up the database first and run only the migration scripts required for the version being deployed. The Bar/Pantry settings migration is safe to run more than once.

Do not blindly import the full SQL dump over an existing live database. Confirm the target database and review the SQL before applying changes.

## Printer setup

The POS manages printer configuration and print jobs. The separate `local-print-terminal/` application runs on the local network and communicates with the POS print API.

### Category printer destinations

Set the printer destination on each product category in POS > Categories:

| Value | Destination | Current behavior |
|---|---|---|
| `0` | None | Does not send the item to the kitchen printer |
| `1` | Kitchen | Included in the kitchen print job using `kitchen_ip` |
| `2` | Bar | Saved as a future destination; Bar routing is not implemented |
| `3` | Pantry | Saved as a future destination; Pantry routing is not implemented |

Items are still recorded in the kitchen order records. Only category items assigned to Kitchen (`catPrinter = 1`) are sent to the kitchen printer in the current implementation.

### Local print terminal

See [`local-print-terminal/README.txt`](local-print-terminal/README.txt) for its installation and API configuration instructions. The local terminal requires a valid API bearer token matching the online POS configuration. Keep that token private and restrict access to the local terminal to the restaurant's LAN.

The local terminal reports a job as printed after successfully writing the ESC/POS payload to the printer socket. This confirms the socket write, not that paper physically came out of the printer.

## Recent application updates

The latest project update includes:

- Bar and Pantry printer IP settings in POS > Settings. These are configuration fields only; Bar/Pantry print routing is not yet enabled.
- HRIS calendar wording changed from Check In to Time In. Internal attendance action values remain unchanged.
- Cart/table assignment safeguards so the table ID from the route is authoritative when adding items, and kitchen orders verify that cart rows belong to the requested table.
- CMS Navigation and Footer page dropdowns populated from `tbl_pages.pageSlug`, displaying and saving routes in the form `/pages/{pageSlug}`. Existing non-page links are retained as current-link options.
- Kitchen ticket heading, table name, and date explicitly centered, with additional top feed spacing.

For the category routing update and detailed deployment notes, see:

- [`CATEGORY-PRINTER-ROUTING-UPDATE.txt`](CATEGORY-PRINTER-ROUTING-UPDATE.txt)
- [`POS-UPDATE-INSTRUCTIONS.txt`](POS-UPDATE-INSTRUCTIONS.txt)

## Deployment checklist

1. Back up the application files and database.
2. Configure the correct database host, database name, username, and password in `application/config/database.php` for the target environment.
3. Configure the correct base URL and other environment-specific CodeIgniter settings.
4. Keep production secrets and API tokens out of Git and web-accessible folders.
5. Deploy the application files and apply the relevant database migrations.
6. Confirm POS login, order entry, table assignment, and kitchen printing.
7. Verify category printer destinations and test the CMS Navigation/Footer page links.
8. Restrict access to administrative tools such as phpMyAdmin and the local print terminal.

## Git notes

The root `.gitignore` excludes the root `_catalog/` directory and common local, generated, and environment-specific files. Review it before committing if the project begins tracking additional runtime assets or dependencies.
