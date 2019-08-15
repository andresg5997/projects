let mix = require('laravel-mix');

mix.js('resources/assets/js/dashboard.js', 'public/js');
mix.js('resources/assets/js/marcas/index.js', 'public/js/marcas');
mix.js('resources/assets/js/marcas/show.js', 'public/js/marcas');
mix.js('resources/assets/js/usuarios/show.js', 'public/js/usuarios');
mix.js('resources/assets/js/usuarios/index.js', 'public/js/usuarios');
mix.js('resources/assets/js/transacciones/index.js', 'public/js/transacciones');
mix.js('resources/assets/js/transacciones/create.js', 'public/js/transacciones');
mix.js('resources/assets/js/tareas/show.js', 'public/js/tareas');
mix.js('resources/assets/js/estados/index.js', 'public/js/estados');
mix.js('resources/assets/js/estados/create.js', 'public/js/estados');
mix.js('resources/assets/js/estados/edit.js', 'public/js/estados');
mix.js('resources/assets/js/oposiciones/index.js', 'public/js/oposiciones');
mix.browserSync('localhost:8000');