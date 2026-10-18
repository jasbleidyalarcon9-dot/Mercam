#!/bin/sh
# Arranque del contenedor en la nube: puerto dinámico, migraciones, datos base y cachés.
set -e

# Railway y Render asignan el puerto en la variable PORT
PORT="${PORT:-8080}"
sed -i "s/^Listen .*/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:[0-9]*>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf

# Si el contenedor se reinicia, se borra la caché anterior para leer las variables actuales
php artisan config:clear >/dev/null 2>&1 || true

# Carpetas que Laravel necesita para escribir (el disco del contenedor arranca limpio)
mkdir -p storage/app/public/demo storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs
php artisan storage:link --force >/dev/null 2>&1 || true

# Imágenes de los productos de demostración
cp -n public/img/*.png storage/app/public/demo/ 2>/dev/null || true

# Base de datos: migraciones + roles + datos de demostración (idempotentes)
php artisan migrate --force
php artisan db:seed --class=RoleSeeder --force
php artisan db:seed --class=DemoSeeder --force

# Cachés de producción
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Apache corre como www-data: debe poder escribir sesiones, logs y vistas compiladas
chown -R www-data:www-data storage bootstrap/cache

# Worker de la cola: envía correos y notificaciones en segundo plano (como www-data).
# Si un correo falla, la página no se cae: el trabajo queda en la tabla failed_jobs.
# El bucle lo reinicia cada hora (--max-time) para liberar memoria.
(
    while true; do
        su -s /bin/sh www-data -c "php artisan queue:work --sleep=3 --tries=3 --backoff=30 --max-time=3600" || true
        sleep 2
    done
) &

exec apache2-foreground
