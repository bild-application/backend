# BILD

[![CI](https://github.com/bild-application/backend/actions/workflows/ci.yml/badge.svg)](https://github.com/bild-application/backend/actions/workflows/ci.yml)

## Set up the Bild back-end

- `docker compose build --no-cache` to build fresh images
- `docker compose up --pull always -d --wait` to start the project
- `docker compose exec php bash` to enter into your symfony service
- `composer install` to install necessary dependencies

## Set up the Minio object-storage

- Go to http://localhost:9001/
- Login with credentials written in .env (variables MINIO_ROOT_USER & MINIO_ROOT_PASSWORD)
- Create a bucket with same name as written in .env (variable MINIO_BUCKET)

## Other docs

- [Aliases](docs/aliases.md)
- [Database](docs/db.md)
- [Tests](docs/testing.md)
- [Https certificate](docs/tls.md)

## Conventions

We defined some conventions to always work in the same way.

### Controller CRUD methods:

| HTTP Verb | URL            | Action / controller method | Route name      | Open api response description |
|-----------|----------------|----------------------------|-----------------|-------------------------------|
| GET       | /articles      | list                       | articles_list   | Articles list                 |
| POST      | /articles      | create                     | articles_create | Article created               |
| GET       | /articles/{id} | get                        | articles_get    | Article                       |
| PATCH     | /articles/{id} | update                     | articles_update | Article updated               |
| DELETE    | /articles/{id} | delete                     | articles_delete | Article deleted               |

### Routing

#### Nesting

Avoid nesting resources when it's possible :

- if a resource can be identified by its own id, do it
- if a resource is necessarily linked to another, you can nest

Good example :

| Action | HTTP Verb | URL                                        | Description                     |
|--------|-----------|--------------------------------------------|---------------------------------|
| get    | GET       | /profiles/{profileId}/contents/{contentId} | Create a content in a profile   |
| delete | DELETE    | /contents                                  | Delete a content                |
| delete | DELETE    | /profiles/{profileId}/contents/{contentId} | Remove a content from a profile |

#### Parameters

Routes should only contain {variables} referring to controller entity.
Good example :

| File              | Action | HTTP Verb | URL       | Body                 |
|-------------------|--------|-----------|-----------|----------------------|
| PackController    | create | POST      | /packs    | {"profile_id": ... } |
| ContentController | create | POST      | /contents | {"profile_id": ... } |

Bad example :

| File              | Action | HTTP Verb | URL                   | Body |
|-------------------|--------|-----------|-----------------------|------|
| PackController    | create | POST      | /packs/{profileId}    | {}   |
| ContentController | create | POST      | /contents/{profileId} | {}   |

### Open api doc

#### Request response description

Should indicate what content is returned
Example :

| HTTP Verb | URL       | Code | Description       |
|-----------|-----------|------|-------------------|
| GET       | /articles | 200  | List of articles  |
| POST      | /articles | 201  | Created article   |
| POST      | /article  | 400  | Validation errors |
