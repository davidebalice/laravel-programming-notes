# Laravel Programming Notes

A personal knowledge base built with Laravel to save programming notes and code snippets, organized by category (technology) and subcategory. Each note is made of text blocks and code blocks edited with an embedded code editor.

The demo contains only a few example notes.

**Live demo:** <https://programming-notes.davidebalice.dev>

![Screenshot](https://www.aroundweb.it/screenshot/programming.jpg)

## Features

- Categories and subcategories with image, sorting and activation
- Notes assigned to one or more categories, with search
- Note content split into ordered text blocks (rich text with Quill) and code blocks (Monaco editor with language selection and syntax highlighting)
- Users management with roles and permissions (spatie/laravel-permission)
- Admin profile and password change
- Authentication with Laravel Breeze
- English and Italian interface
- Demo mode that blocks write operations

## Tech stack

- [Laravel 12](https://laravel.com/)
- PHP 8.2+
- MySQL
- [spatie/laravel-permission](https://spatie.be/docs/laravel-permission)
- [Intervention Image](https://image.intervention.io/) for image resizing
- [Monaco Editor](https://microsoft.github.io/monaco-editor/), [Quill](https://quilljs.com/) and [highlight.js](https://highlightjs.org/)
- [Vite](https://vite.dev/), [Tailwind CSS 4](https://tailwindcss.com/) and [Alpine.js](https://alpinejs.dev/) for the authentication pages
- Bootstrap based admin template

## Getting started

### Requirements

- PHP 8.2 or later
- Composer
- Node.js 20 or later
- MySQL or MariaDB

### Installation

```bash
git clone https://github.com/davidebalice/laravel-programming-notes.git
cd laravel-programming-notes

composer install
npm install

cp .env.example .env
php artisan key:generate
```

Update the database settings in `.env`, then run the migrations:

```bash
php artisan migrate
```

### Run

```bash
npm run build
php artisan serve
```

The admin panel is available at `http://localhost:8000/admin/login`.

During development you can run `npm run dev` to start the Vite dev server with hot reload.

### Demo mode

Set `DEMO_MODE=true` in `.env` to prevent create, update and delete operations.

## Author

**Davide Balice** – [davidebalice.dev](https://www.davidebalice.dev)
