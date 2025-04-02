<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StyleSphere</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-screen bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center text-white">
    <div class="text-center p-10 bg-white bg-opacity-10 backdrop-blur-md rounded-lg shadow-lg">
        <h1 class="text-5xl font-bold mb-6">Welcome to <span class="text-yellow-300">StyleSphere</span></h1>
        <p class="text-lg mb-8">Your ultimate fashion destination</p>
        <div class="space-x-4">
            <a href="<?php echo e(url('/login')); ?>" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 transition-all rounded-lg shadow-md text-white text-lg">Login</a>
            <a href="<?php echo e(url('/register')); ?>" class="px-6 py-3 bg-green-500 hover:bg-green-600 transition-all rounded-lg shadow-md text-white text-lg">Register</a>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\stylesphere\resources\views/landing.blade.php ENDPATH**/ ?>