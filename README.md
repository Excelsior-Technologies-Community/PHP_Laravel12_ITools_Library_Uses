# PHP_Laravel12_ITools_Library_Uses

##  Introduction

The PHP_Laravel12_ITools_Library_Uses is a modern web application built using Laravel 12, designed to manage and maintain a digital collection of tools efficiently.

The primary purpose of this project is to allow users to add new tools, while also automatically tracking every change using a reliable changelog system.

This project uses UUID-based primary keys for enhanced security and scalability. It integrates the ltools package to automatically record all create, update, and delete actions in a dedicated ltools_changelog_items table.

This ensures a complete audit trail of all tool-related activities, making it ideal for enterprise-level applications where data integrity, traceability, and accountability are essential.

---

##  Project Overview

- Laravel 12 project
- Add New Tools
- UUID primary key for tools table
- Uses `vcoder7/ltools` package for change logging
- Uses Tailwind CSS for UI
- Database: MySQL

---

##  Tech Stack

-   **Laravel 12**
-   **PHP 8.2+**
-   **MySQL**
-   **Blade Templates**
-   **Bootstrap 5**
-   **XAMPP**

---


##  Step 1: Create Laravel Project

```bash
composer create-project laravel/laravel PHP_Laravel12_ITools_Library_Uses "12.*"
cd PHP_Laravel12_ITools_Library_Uses
```

---

## Step 2: Configure Database

Open .env and set:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=itools_library
DB_USERNAME=root
DB_PASSWORD=

Then run this command to create database

```bash
php artisan migrate
```

---

## Step 3: Install ltools Package

```
composer require vcoder7/ltools
```

---

## Step 4: Publish ltools Migrations

```
php artisan vendor:publish --tag=ltools-migrations
```

---

## Step 5: Create Tools Migration

```
php artisan make:migration create_tools_table
```

File: database/migrations/2025_07_05_132639_create_tools_table.php

Then update migration file:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tools', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('category');
            $table->text('description');
            $table->string('website')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tools');
    }
};
```

---

## Step 6: Fix ltools Migration (Important)

Open:

database/migrations/xxxx_add_ltools_changelog_items_table.php


Update migration as below:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(config('ltools.table_name_changelog_items'), function (Blueprint $table) {
            $table->id();
            $table->string('model', 191);
            $table->string('model_id', 191);
            $table->json('changes');
            $table->bigInteger('user_id')->nullable();
            $table->uuid('uuid')->unique();
            $table->timestamp('created_at')->useCurrent();
            $table->index(['model', 'model_id'], 'IDX_model__model_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(config('ltools.table_name_changelog_items'));
    }
};
```

---

## Step 7: Run Migrations

```
php artisan migrate
```

---

## Step 8: Create Tool Model

```bash
php artisan make:model Tool
```

File: app/Models/Tool.php

Then update:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Vcoder7\Ltools\Http\Traits\RecordChangesTrait;

class Tool extends Model
{
    use HasUuids, RecordChangesTrait;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'category',
        'description',
        'website',
    ];
}
```

---

## Step 9: Create Tool Controller

```bash
php artisan make:controller ToolController
```

File: app/Http/Controllers/ToolController.php

```php
<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use Illuminate\Http\Request;

class ToolController extends Controller
{
    public function index()
    {
        $tools = Tool::latest()->get();
        return view('tools.index', compact('tools'));
    }

    public function create()
    {
        return view('tools.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'category' => 'required',
            'description' => 'required',
        ]);

        Tool::create($request->all());

        return redirect()->route('tools.index')
            ->with('success', 'Tool created successfully');
    }
}
```

---

## Step 10: Web Routes

File: routes/web.php:

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ToolController;

Route::get('/tools', [ToolController::class, 'index'])->name('tools.index');
Route::get('/tools/create', [ToolController::class, 'create'])->name('tools.create');
Route::post('/tools', [ToolController::class, 'store'])->name('tools.store');


Route::get('/', function () {
    return view('welcome');
});
```

---

## Step 11: Blade Files

### 11.1) Index.blade.php

File: resources/views/tools/index.blade.php

```html
<!DOCTYPE html>
<html>
<head>
    <title>ITools Library</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100">

<div class="max-w-7xl mx-auto py-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">ITools Library</h2>
        <a href="{{ route('tools.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded">
            Add New Tool
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow rounded p-4">
        @if ($tools->count() > 0)
            <table class="min-w-full">
                <thead>
                    <tr>
                        <th class="text-left px-4 py-2">Name</th>
                        <th class="text-left px-4 py-2">Category</th>
                        <th class="text-left px-4 py-2">Website</th>
                        <th class="text-left px-4 py-2">Description</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tools as $tool)
                        <tr>
                            <td class="border px-4 py-2">{{ $tool->name }}</td>
                            <td class="border px-4 py-2">{{ $tool->category }}</td>
                            <td class="border px-4 py-2">
                                @if($tool->website)
                                    <a href="{{ $tool->website }}" target="_blank" class="text-blue-500 underline">
                                        Visit
                                    </a>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="border px-4 py-2">{{ $tool->description }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No tools found.</p>
        @endif
    </div>
</div>

</body>
</html>
```

### 11.2) Create.blade.php

File: resources/views/tools/create.blade.php

```html
<!DOCTYPE html>
<html>
<head>
    <title>Add New Tool</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100">

<div class="max-w-3xl mx-auto py-8">
    <h2 class="text-2xl font-bold mb-6">Add New Tool</h2>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('tools.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="block font-semibold">Tool Name</label>
            <input type="text" name="name" value="{{ old('name') }}"
                class="w-full border rounded px-3 py-2 mt-1" placeholder="Enter tool name">
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Category</label>
            <input type="text" name="category" value="{{ old('category') }}"
                class="w-full border rounded px-3 py-2 mt-1" placeholder="Enter category">
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Website (optional)</label>
            <input type="url" name="website" value="{{ old('website') }}"
                class="w-full border rounded px-3 py-2 mt-1" placeholder="Enter website link">
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Description</label>
            <textarea name="description" rows="5"
                class="w-full border rounded px-3 py-2 mt-1" placeholder="Enter description">{{ old('description') }}</textarea>
        </div>

        <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded">
            Save Tool
        </button>
    </form>
</div>

</body>
</html>
```

---

## Step 12: ChangelogItem.php

File : vendor/vcoder7/ltools/src/Models/ChangelogItem.php

Find this line:

private const string DEFAULT_TABLE_NAME = 'ltools_changelog_items';


And update it like this:

private const DEFAULT_TABLE_NAME = 'ltools_changelog_items';

---


##  Project Structure

```
PHP_Laravel12_ITools_Library_Uses/
│
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── ToolController.php
│   │   └── ...
│   ├── Models/
│   │   └── Tool.php
│   └── ...
│
├── database/
│   └── migrations/
│       ├── 2025_07_05_132639_create_tools_table.php
│       └── 2025_07_05_132639_add_ltools_changelog_items_table.php
│
├── resources/
│   └── views/
│       └── tools/
│           ├── index.blade.php
│           └── create.blade.php
│
├── routes/
│   └── web.php
│
├── vendor/
│   └── vcoder7/
│       └── ltools/
│           └── src/
│               └── Models/
│                   └── ChangelogItem.php
│
├── .env
├── composer.json
└── README.md
```
---

## Output

### Add New Tool

<img width="1919" height="1026" alt="Screenshot 2026-01-20 105742" src="https://github.com/user-attachments/assets/5fc395d8-6c96-42f3-b7c4-78d5206c9cef" />

### Index Page

<img width="1919" height="1032" alt="Screenshot 2026-01-20 105751" src="https://github.com/user-attachments/assets/8f3c9c03-cb05-42e1-9f44-4ba5e210e5c4" />

---

Your PHP_Laravel12_ITools_Library_Uses Project is Now Ready!
