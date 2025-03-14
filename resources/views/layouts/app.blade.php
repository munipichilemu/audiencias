<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - @yield('title', 'Dashboard')</title>
    <!-- Agrega tus estilos (por ejemplo, TailwindCSS) -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="min-h-screen flex flex-col">
        <!-- Encabezado -->
        <header class="bg-indigo-500 text-white p-4">
            <h1 class="text-xl font-bold">{{ config('app.name') }}</h1>
        </header>

        <!-- Contenido principal -->
        <main class="flex-grow p-6">
            @yield('content') <!-- Aquí se insertará el contenido de cada página -->
        </main>

        <!-- Pie de página -->
        <footer class="bg-gray-800 text-white py-2 text-center text-sm">
            &copy; {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.
        </footer>
    </div>
</body>
</html>
