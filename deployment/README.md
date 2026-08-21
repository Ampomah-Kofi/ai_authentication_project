# Deployment Package

This folder contains everything needed to deploy the study application. Configure the web server document root as `deployment/public`; do not expose the entire `deployment` folder.

## Contents

- `public/` - the only web-accessible folder: participant page, public configuration, and two PHP POST endpoints
- `src/` - shared server code included by the endpoints; keep outside the web root
- `database/` - MariaDB schema and grant examples; never serve publicly
- `.env.example` - configuration names with placeholders; never place real secrets in this file or the repository

Production configuration, database credentials, logs, backups, and participant data are not part of this package and must remain in University-managed protected services.
