#  PHP_Laravel12_Lang

<p align="center">
    <img src="https://img.shields.io/badge/Laravel-12-red?style=for-the-badge&logo=laravel" />
    <img src="https://img.shields.io/badge/PHP-8.2+-blue?style=for-the-badge&logo=php" />
    <img src="https://img.shields.io/badge/Multi--Language-Supported-success?style=for-the-badge" />
    <img src="https://img.shields.io/badge/License-MIT-green?style=for-the-badge" />
</p>


---

#  Overview

This project demonstrates Multi-Language Localization in Laravel 12.

It includes:

* Dynamic language switching
* Validation message translation
* Success message translation
* Session-based locale handling
* Clean UI form design

Supported Languages:

* English (en)
* Hindi (hi)
* Gujarati (gu)
* Spanish (es)
* French (fr)

---

#  Features

* Multi-language support (English, Hindi, Gujarati, Spanish, French)
* Dynamic language switching using dropdown
* Session-based locale management
* Translated validation error messages
* Translated success messages using JSON files
* Clean and responsive UI design
* Laravel 12 compatible structure
* Easy to extend for additional languages

---

#  Folder Structure

```
PHP_Laravel12_Lang/
│
├── app/
├── bootstrap/
├── config/
├── database/
├── public/
├── resources/
│   └── views/
│       └── form.blade.php
│
├── routes/
│   └── web.php
│
├── lang/
│   ├── en/
│   ├── hi/
│   ├── gu/
│   ├── es/
│   ├── fr/
│   ├── en.json
│   ├── hi.json
│   ├── gu.json
│   ├── es.json
│   └── fr.json
│
├── storage/
├── vendor/
├── .env
├── artisan
└── composer.json
```

---

# 1️ System Requirements

Before starting, make sure your system has:

* PHP 8.2 or higher
* Composer
* MySQL
* Laravel 12 compatible environment

Check installed versions:

```bash
php -v
composer -v
```

---

# 2️ Create Laravel 12 Project

```bash
composer create-project laravel/laravel multilingual-app
```

Start server:

```bash
php artisan serve
```

Open in browser:

```
http://127.0.0.1:8000
```

---

# 3️ Configure Environment File

Open `.env` file and configure:

```env
APP_NAME=Laravel
APP_ENV=local
APP_KEY=Your_Key
APP_DEBUG=true
APP_URL=http://localhost

APP_LOCALE=en

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lang
DB_USERNAME=root
DB_PASSWORD=
```

Clear configuration cache:

```bash
php artisan config:clear
```

---

# 4️ Install Laravel Language Package

```bash
composer require laravel-lang/lang
```

---

# 5️ Add Multiple Languages

```bash
php artisan lang:add en
php artisan lang:add hi
php artisan lang:add gu
php artisan lang:add es
php artisan lang:add fr
```

This will create:

```
lang/
 ├── en/
 ├── hi/
 ├── gu/
 ├── es/
 ├── fr/
```

---

# 6️ Configure Routes

Open `routes/web.php` and replace with:

```php
<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

Route::get('/lang/{locale}', function ($locale) {

    $availableLocales = ['en', 'hi', 'gu', 'es', 'fr'];

    if (in_array($locale, $availableLocales)) {
        session(['locale' => $locale]);
    }

    return back();
});

Route::get('/form', function () {

    if (session()->has('locale')) {
        App::setLocale(session('locale'));
    }

    return view('form');
});

Route::post('/form', function (Request $request) {

    if (session()->has('locale')) {
        App::setLocale(session('locale'));
    }

    $request->validate([
        'email' => 'required|email',
        'name'  => 'required|min:3',
    ]);

    return back()->with('success', __('Form Submitted Successfully'));
});
```

---

# 7️ Create Form View

Create file:

```
resources/views/form.blade.php
```

Paste the following code:

```html
<!DOCTYPE html>
<html>
<head>
    <title>Multilingual Test Form</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea, #764ba2);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }

        .card {
            background: #ffffff;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            width: 380px;
        }

        h2 {
            text-align: center;
            margin-bottom: 15px;
            color: #333;
        }

        .lang-select {
            text-align: center;
            margin-bottom: 20px;
        }

        select {
            padding: 6px 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            cursor: pointer;
        }

        .error-box {
            background: #ffe6e6;
            border: 1px solid red;
            padding: 10px 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            color: red;
        }

        .success-box {
            background: #e6ffed;
            border: 1px solid green;
            padding: 10px 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            color: green;
            text-align: center;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 14px;
            transition: 0.3s;
        }

        input:focus {
            border-color: #667eea;
            outline: none;
            box-shadow: 0 0 5px rgba(102, 126, 234, 0.5);
        }

        button {
            width: 100%;
            padding: 10px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #5a67d8;
        }
    </style>
</head>
<body>

<div class="card">

    <h2>Test Form</h2>

    <div class="lang-select">
        <select onchange="window.location.href='/lang/' + this.value;">
            <option value="hi" {{ app()->getLocale() == 'hi' ? 'selected' : '' }}>Hindi</option>
            <option value="gu" {{ app()->getLocale() == 'gu' ? 'selected' : '' }}>Gujarati</option>
            <option value="es" {{ app()->getLocale() == 'es' ? 'selected' : '' }}>Spanish</option>
            <option value="fr" {{ app()->getLocale() == 'fr' ? 'selected' : '' }}>French</option>
            <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>English</option>
        </select>
    </div>

    @if(session('success'))
        <div class="success-box">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="error-box">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="/form">
        @csrf
        <input type="text" name="name" placeholder="Enter Name" value="{{ old('name') }}">
        <input type="text" name="email" placeholder="Enter Email" value="{{ old('email') }}">
        <button type="submit">Submit</button>
    </form>

</div>

</body>
</html>
```

---

# 8️ Add Attribute Translations (ALL LANGUAGES)

### English — `lang/en/validation.php`

```php
'attributes' => [
    'name' => 'Name',
    'email' => 'Email',
],
```

### Hindi — `lang/hi/validation.php`

```php
'attributes' => [
    'name' => 'नाम',
    'email' => 'ईमेल',
],
```

### Gujarati — `lang/gu/validation.php`

```php
'attributes' => [
    'name' => 'નામ',
    'email' => 'ઈમેલ',
],
```

### Spanish — `lang/es/validation.php`

```php
'attributes' => [
    'name' => 'nombre',
    'email' => 'correo electrónico',
],
```

### French — `lang/fr/validation.php`

```php
'attributes' => [
    'name' => 'nom',
    'email' => 'adresse e-mail',
],
```

Clear cache:

```bash
php artisan cache:clear
```

---

# 9️ Add Success Message Translation (ALL LANGUAGES)

### `lang/en.json`

```json
{
  "Form Submitted Successfully": "Form Submitted Successfully"
}
```

### `lang/hi.json`

```json
{
  "Form Submitted Successfully": "फ़ॉर्म सफलतापूर्वक जमा किया गया।"
}
```

### `lang/gu.json`

```json
{
  "Form Submitted Successfully": "ફોર્મ સફળતાપૂર્વક સબમિટ થયું."
}
```

### `lang/es.json`

```json
{
  "Form Submitted Successfully": "Formulario enviado con éxito."
}
```

### `lang/fr.json`

```json
{
  "Form Submitted Successfully": "Formulaire soumis avec succès."
}
```

Run:

```bash
php artisan cache:clear
php artisan config:clear
```

---

# Test the Application

Start server:

```bash
php artisan serve
```

Open:

```
http://127.0.0.1:8000/form
```

---

#  Expected Results

## Case 1: Form Submitted with Valid Data

Input:

```
Name: HARRY
Email: harry@gmail.com
```

### Gujarati

```
ફોર્મ સફળતાપૂર્વક સબમિટ થયું.
```
<img width="609" height="481" alt="Screenshot 2026-02-06 131528" src="https://github.com/user-attachments/assets/e511ea8c-7601-433d-86d1-7e180d013e99" />


### Hindi

```
फ़ॉर्म सफलतापूर्वक जमा किया गया।
```
<img width="570" height="452" alt="Screenshot 2026-02-06 131444" src="https://github.com/user-attachments/assets/f9501237-fe1e-45d7-87a7-323d52512029" />


### Spanish

```
Formulario enviado con éxito.
```
<img width="633" height="458" alt="Screenshot 2026-02-06 131602" src="https://github.com/user-attachments/assets/2d33b35c-17dd-4dc1-9e88-fd1f25cde77b" />


### French

```
Formulaire soumis avec succès.
```
<img width="596" height="449" alt="Screenshot 2026-02-06 131650" src="https://github.com/user-attachments/assets/ca2c6e9f-1309-41ea-86cb-9da24eabb06c" />


### English

```
Form Submitted Successfully
```
<img width="640" height="449" alt="Screenshot 2026-02-06 131710" src="https://github.com/user-attachments/assets/f6454d24-d1ce-48f3-a9ec-909d049fd219" />


---

## Case 2: Submit Without Filling Form (Validation Errors)

### Gujarati

```
ઈમેલ આવશ્યક છે.
નામ આવશ્યક છે.
```
<img width="624" height="508" alt="Screenshot 2026-02-06 142819" src="https://github.com/user-attachments/assets/fd0cbafb-1224-4707-9653-11867bc69ccc" />


### Hindi

```
ईमेल फ़ील्ड आवश्यक है।
नाम फ़ील्ड आवश्यक है।
```
<img width="660" height="501" alt="Screenshot 2026-02-06 130018" src="https://github.com/user-attachments/assets/8eda93ee-e134-4716-bb64-398c2850a8f4" />


### Spanish

```
El campo correo electrónico es obligatorio.
El campo nombre es obligatorio.
```
<img width="621" height="470" alt="Screenshot 2026-02-06 130058" src="https://github.com/user-attachments/assets/5e0deff8-9d65-4f6b-aa7f-ec2350afd56b" />


### French

```
Le champ adresse e-mail est obligatoire.
Le champ nom est obligatoire.
```
<img width="618" height="475" alt="Screenshot 2026-02-06 130109" src="https://github.com/user-attachments/assets/363c9ffa-83b2-41cb-a256-959ccf8ea9e4" />


### English

```
The Email field is required.
The Name field is required.
```
<img width="628" height="472" alt="Screenshot 2026-02-06 130008" src="https://github.com/user-attachments/assets/34e60946-abfa-4864-b4e2-30de52ab790f" />


---

## Case 3: Invalid Email Format

Input:

```
Name: HARRY
Email: abc
```

### Gujarati

```
ઈમેલ માન્ય ઈમેલ એડ્રેસ હોવું જોઈએ.
```
<img width="606" height="512" alt="Screenshot 2026-02-06 132428" src="https://github.com/user-attachments/assets/5de87d73-8994-4a0e-b755-0c65316bfb13" />


### Hindi

```
ईमेल एक मान्य ईमेल पता होना चाहिए।
```
<img width="573" height="484" alt="Screenshot 2026-02-06 132348" src="https://github.com/user-attachments/assets/1defd5ea-6171-43ba-b241-350b64c5ea31" />


### Spanish

```
El campo correo electrónico debe ser una dirección de correo válida.
```
<img width="605" height="531" alt="Screenshot 2026-02-06 132413" src="https://github.com/user-attachments/assets/49fef671-bac5-4451-8f98-83b4606fa27d" />


### French

```
Le champ adresse e-mail doit être une adresse e-mail valide.
```
<img width="611" height="511" alt="Screenshot 2026-02-06 132335" src="https://github.com/user-attachments/assets/82288a2a-084f-49ad-8f0f-b86cf8d53e1c" />


### English

```
The Email field must be a valid email address.
```
<img width="615" height="530" alt="Screenshot 2026-02-06 132450" src="https://github.com/user-attachments/assets/cb7c380c-8630-4366-af06-0504a3b1797d" />


---

#  Final Outcome

*  Fully working multilingual form
*  Dynamic language switching
*  Validation translation
*  Success message translation
*  Clean UI design

---


