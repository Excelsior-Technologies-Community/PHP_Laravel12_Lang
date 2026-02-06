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

    <!-- Language Dropdown -->
    <div class="lang-select">
        <select onchange="window.location.href='/lang/' + this.value;">
            <option value="hi" {{ app()->getLocale() == 'hi' ? 'selected' : '' }}>Hindi</option>
            <option value="gu" {{ app()->getLocale() == 'gu' ? 'selected' : '' }}>Gujarati</option>
            <option value="es" {{ app()->getLocale() == 'es' ? 'selected' : '' }}>Spanish</option>
            <option value="fr" {{ app()->getLocale() == 'fr' ? 'selected' : '' }}>French</option>
            <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>English</option>
        </select>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="success-box">
            {{ session('success') }}
        </div>
    @endif

    <!-- Validation Errors -->
    @if ($errors->any())
        <div class="error-box">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form -->
    <form method="POST" action="/form">
        @csrf
        <input type="text" name="name" placeholder="Enter Name" value="{{ old('name') }}">
        <input type="text" name="email" placeholder="Enter Email" value="{{ old('email') }}">
        <button type="submit">Submit</button>
    </form>

</div>

</body>
</html>
