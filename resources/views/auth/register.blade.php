<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Register</title>
</head>
<body>
    <div class="bg-blue-300 min-h-screen flex items-center justify-center">
        <div class="container mx-auto px-4">
            <form action="{{ route('register.store') }}" method="POST" class="w-full max-w-sm mx-auto mt-10 bg-white p-6 rounded-lg shadow-md">
                <h1 class="text-3xl font-bold text-center mt-10">Register</h1>
                @csrf
                @if ($errors->any())
                    <div class="mt-4 rounded-lg bg-red-100 p-3 text-sm text-red-700">
                        {{ $errors->first() }}
                    </div>
                @endif
                <div class="mb-4">
                    <label for="nis" class="block text-gray-700 font-bold mb-2">NIS</label>
                    <input type="number" id="nis" name="nis" value="{{ old('nis') }}" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-300">
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-gray-700 font-bold mb-2">Password</label>
                    <input type="password" id="password" name="password" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-300">
                </div>
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-gray-700 font-bold mb-2">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-300">
                </div>
                <div class="mt-4 text-center justify-between flex items-center">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring">
                        Register
                    </button>
                    <a href="{{ route('login') }}" class="text-blue-500 hover:text-blue-700 font-bold py-2 px-4 rounded focus:outline-none focus:ring">
                        Kembali ke Login
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
