# Backend API for Blog

This repository contains the backend API for the Blog project. The API provides both public endpoints for distributing news articles and an authentication system for the admin panel, allowing the creation and editing of news and other data.

## Routes

The following routes are available in the API:

- `POST /auth`: Endpoint for user authentication. Used for obtaining a token to access admin features.
- `POST /check`: Endpoint for validating the authentication token.

Public Endpoints:

- `GET /pages/all`: Retrieve a list of all pages.
- `GET /getPages`: Retrieve all pages.
- `GET /getContent/{page}`: Retrieve the content of a specific page.
- `GET /carousel/get`: Get the carousel items.
- `GET /news/get/`: Get all news articles.
- `GET /news/get/limit/{limit}`: Get a specified number of news articles.
- `GET /news/get/{slug}`: Get a specific news article by slug.
- `GET /videos/get`: Get all videos.
- `GET /videos/get/limit/{limit}`: Get a specified number of videos.
- `GET /videos/get/{slug}`: Get a specific video by slug.
- `GET /podcasts/get`: Get all podcasts.
- `GET /podcasts/get/limit/{limit}`: Get a specified number of podcasts.
- `GET /podcasts/get/{slug}`: Get a specific podcast by slug.
- `GET /getLandingPage`: Retrieve the landing page content.

Authenticated Endpoints:

The following endpoints require a valid authentication token.

- `POST /newPage`: Create a new page.
- `PUT /updatePage`: Update an existing page.
- `PUT /contato/update`: Update contact information.
- `PUT /setOrder`: Set the order of pages.
- `DELETE /deletePage/{page}`: Delete a page.

- `POST /carousel/new`: Create a new carousel item.
- `DELETE /carousel/delete/{id}`: Delete a carousel item.

- `POST /news/new`: Create a new news article.
- `DELETE /news/delete/{id}`: Delete a news article.

- `POST /saveImage`: Save an uploaded image file.

## Technology Used

The backend API is built using Laravel, a popular PHP framework known for its simplicity and robustness. Laravel provides a solid foundation for building secure and scalable web applications.

## Setup Instructions

To set up the backend API locally, follow these steps:

1. Clone the repository:

   ```
   git clone https://github.com/thiagoHagge/blog-api.git
   ```

2. Install the dependencies:

   ```
   cd blog-backend
   composer install
   ```

3. Set up the database configuration by creating a `.env` file based on the `.env.example` file.

4. Generate a new application key:

   ```
   php artisan key:generate
   ```

5. Migrate the database:

   ```
   php artisan migrate
   ```

6. Start the development server:

   ```
   php artisan serve
   ```

7. Access the API at [http://localhost:8000](http://localhost:8000).
