# BetterCom — Triumphant College IT Support & Communication Management

A PHP/MySQL web application foundation designed for Railway deployment. Includes role-based access, student registration approval, academic levels/groups, lecturer module allocation, announcements, direct messages, IT ticket/call logging, technician updates, IT asset register, reports, meeting scheduling and LiveKit-powered browser meeting rooms.

## Important scope and integrations
- eLearning and TCMS are treated as external systems. Tickets about them can be recorded and referred; they are not assigned as ordinary college IT repair work.
- Live audio/video uses LiveKit Cloud. Meeting scheduling is available in the application, but live media requires a LiveKit project and Railway variables. This repository does not include a LiveKit account or credentials.
- Object storage, email delivery, recurring timetable, meeting recording/egress, group-chat moderation, module file uploads and external TCMS/eLearning APIs need institution-approved providers/credentials and further implementation before production use. Do not treat the demo as a certified production deployment.
- Website colour sampling was limited by the site fetch; the theme is based on an institutional navy/blue with restrained gold accent, not a verified official colour-code extraction. Replace logo/colour tokens if the college supplies official brand assets.

## Local testing (optional)
1. Create a MySQL database named `bettercom`.
2. Import `schema.sql`.
3. Set DB environment variables or use defaults in `config.php`.
4. Serve this directory with PHP 8.3+ and MySQLi enabled.
5. Create the first admin directly in MySQL (see below).

## Create first administrator
Generate a password hash with PHP:
```php
<?php echo password_hash('ChangeThisStrongPassword!', PASSWORD_DEFAULT);
```
Then insert:
```sql
INSERT INTO users(name,email,password_hash,role,status)
VALUES ('System Administrator','admin@yourcollege.na','PASTE_HASH_HERE','admin','active');
```
Log in and create staff accounts and academic levels/groups.

## Railway deployment
1. Push this folder to a GitHub repository.
2. Create a Railway project and deploy the repository using the included Dockerfile.
3. Add a Railway MySQL service and import `schema.sql` into its database (use the MySQL service's connection variables).
4. Set application variables on the PHP service:
   - `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASS`
   - `APP_URL`, `APP_NAME=BetterCom`
   - `LIVEKIT_URL`, `LIVEKIT_API_KEY`, `LIVEKIT_API_SECRET`
5. Generate and insert the initial admin hash as above.
6. Add a custom domain and HTTPS; test registration, role restrictions, tickets, and meetings.
7. Configure backups, monitoring, object storage and institutional data-protection approval before real student data is used.

## Security / production checklist
- Rotate all passwords and use unique administrator credentials.
- Restrict admin creation; only admins can create staff accounts.
- Configure email verification/password reset before public registration.
- Add rate limiting, account lockout, security headers and file scanning.
- Validate all audience IDs against actual membership; review group and module visibility before launch.
- Configure LiveKit room/participant limits and recording consent if recording is enabled.
- Add database migrations, automated tests, audit review and backups.
- The initial implementation is an extensible working MVP, not a fully audited enterprise system.
