# Faceless AI Toolkit

A comprehensive AI automation platform built with Laravel 11. This toolkit enables users to generate content using various AI tools, build automation workflows, and manage their content library.

## Features

-   **AI Tools Directory**: Browsable directory of generative AI tools.
-   **Tool Runner**: Interactive interface to execute tools (Mocked AI integration).
-   **Automation Workflows**: Build multi-step workflows to chain tool executions.
-   **Content Library**: Save and organize generated content into collections.
-   **Blog System**: SEO-friendly blog for content marketing.
-   **Admin Panel**: Manage users, tools, and system settings.
-   **Multi-Theme System**: 'Light Modern' and 'Dark Neon' themes included.

## Installation

1.  **Clone the repository**
    ```bash
    git clone https://github.com/yourusername/faceless-ai-toolkit.git
    cd faceless-ai-toolkit
    ```

2.  **Install Dependencies**
    ```bash
    composer install
    npm install && npm run build
    ```

3.  **Configure Environment**
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
    Update your `.env` file with your database credentials.

4.  **Database Setup**
    ```bash
    php artisan migrate --seed
    ```
    *This creates the database tables and seeds initial data including an admin user and 12 example tools.*

5.  **Run the Application**
    ```bash
    php artisan serve
    ```

## Usage

### User Accounts
-   **Admin**: Email: `admin@faceless.ai`, Password: `password`
-   **User**: Email: `user@faceless.ai`, Password: `password`

### Key Routes
-   Home: `/`
-   Tools: `/tools`
-   Dashboard: `/dashboard`
-   Admin Dashboard: `/admin/dashboard`
-   My Workflows: `/workflows`

## Tech Stack

-   **Framework**: Laravel 11
-   **Frontend**: Blade, Alpine.js, Tailwind CSS
-   **Auth**: Laravel Breeze
-   **Database**: MySQL

## License

The Faceless AI Toolkit is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
