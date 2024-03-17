# BILD

## Set up the Bild back-end

- `docker compose build --no-cache` to build fresh images
- `docker compose up --pull always -d --wait` to start the project
- `docker compose exec php bash` to enter into your symfony service
- `composer install` to install necessary dependencies

## Set up the Minio object-storage

- Go to http://localhost:9001/
- Login with credentials written in .env (variables MINIO_ROOT_USER & MINIO_ROOT_PASSWORD)
- Create a bucket with same name as written in .env (variable MINIO_BUCKET)
