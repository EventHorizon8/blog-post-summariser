# Blog post summariser - backend

### Module explanation

This module provides a backend service for summarizing blog posts using OpenAI's GPT-4 model. It accepts a URL of a blog post, fetches its content, and generates a concise summary.

Frontend module is available here with screenshots:
https://github.com/EventHorizon8/front-blog-post-summariser


### Stack
- PHP >= 8.4
- Laravel 12
- PostgreSQL >=16
- Docker

### Setup local environment

```shell

# 1. Setup .env file - the dist is ready to use
cp .env.example .env

# 2. Run Docker environment
docker compose up

# 3. Get inside fpm container (to run php-based commands)
docker compose exec fpm sh

# 4. Install dependencies: (run inside fpm container)
composer install

# 5. Migrate the Database: (run inside fpm container)
php artisan migrate

```

```dotenv

# Set up OPENAI_API_KEY and OPENAI_ORGANIZATION in .env file
# You can get keys in your OpenAI account - https://platform.openai.com/settings/organization/api-keys
OPENAI_API_KEY=
# Organization ID - https://platform.openai.com/settings/organization/general
OPENAI_ORGANIZATION=

```

### Testing

```shell
# Run all Unit Tests
php artisan test
```
