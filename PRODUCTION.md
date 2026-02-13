# Production Deployment Guide

This guide explains how to manually deploy the application to your VPS after the GitHub Action CI has successfully pushed the Docker image to Docker Hub.

## Prerequisites

1.  **VPS** with Docker and Docker Compose installed.
2.  **Docker Hub Credentials** stored in GitHub Secrets (`DOCKER_USERNAME`, `DOCKER_PASSWORD`).
3.  **Environment Variables**: A `.env.prod` file on your VPS with production values.

## Deployment Steps

### 1. SSH into your VPS
```bash
ssh your-user@your-vps-ip
```

### 2. Prepare the Deployment Directory
Create a folder for the project if you haven't already:
```bash
mkdir -p citizen-complaint
cd citizen-complaint
```

### 3. Copy `docker-compose.prod.yml` and `.env`
Ensure you have the `docker-compose.prod.yml` file and a `.env` file with the following variables:
- `DOCKER_USERNAME`: Your Docker Hub username.
- `DOMAIN`: Your production domain (e.g., `complaint.example.com`).
- `EMAIL`: Your email for Let's Encrypt notifications.
- `DB_HOST`, `REDIS_HOST`, etc. for external services.
- `AWS_ACCESS_KEY_ID`, `AWS_SECRET_ACCESS_KEY`, `AWS_DEFAULT_REGION`, `AWS_BUCKET`: Credentials for your external S3 storage.
- `MAIL_MAILER`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_ENCRYPTION`, `MAIL_FROM_ADDRESS`: SMTP credentials for sending verification emails.

> [!NOTE]
> This setup uses **automatic HTTPS** via Let's Encrypt and supports **external Database, Redis, S3, and Email (SMTP)**. Ensure all credentials in `.env` are correct and your VPS has internet access to these services.

### 4. Pull the Latest Image
```bash
docker compose -f docker-compose.prod.yml pull
```

### 5. Start the Application
```bash
docker compose -f docker-compose.prod.yml up -d
```

### 6. Run Database Migrations
After the containers are up and running, execute the migrations:
```bash
docker exec -it citizen-complaint-app php artisan migrate --force
```

### 7. Post-Deployment Checks
- Verify if the application is accessible on your domain or IP (Port 80).
- Check logs if something goes wrong:
  ```bash
  docker compose -f docker-compose.prod.yml logs -f app
  ```

## Automatic Deployment (Next Level)
To automate this "SSH & Pull" process, you can extend the CI workflow with an `ssh-action` to run these commands automatically whenever a push to `main` completes.
