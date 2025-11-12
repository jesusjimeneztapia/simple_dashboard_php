# 🧾 Configuración de `.htaccess` y Reescritura de URLs

Este documento describe **cómo habilitar y verificar el soporte de** `.htaccess` **y** `mod_rewrite` en distintos entornos donde puede ejecutarse el proyecto.

El archivo `.htaccess` se usa para permitir **URLs limpias** y redirigir todas las peticiones al archivo `index.php` del módulo (router principal).

## 🧱 Contenido

**1. Configuración del entorno**

- [✅ Laragon (Windows) **↗**](#-laragon-windows)
- [✅ XAMPP (Windows) **↗**](#-xampp-windows)
- [✅ Apache en Linux / Manual **↗**](#-apache-linux--configuración-manual)
- [✅ cPanel / Hosting Compartido **↗**](#-cpanel--hosting-compartido)
- [✅ Ngnix **↗**](#-nginx)

## 🌐 Configuración por entorno

### ✅ Laragon (Windows)

Laragon crea automáticamente VirtualHosts con `AllowOverride All`, por lo que normalmente **no necesitas hacer nada**.

Sin embargo, puedes verificarlo o ajustarlo manualmente:

1. Abrir el archivo principal de Apache:

```text
C:\laragon\bin\apache\httpd-<versión>\conf\httpd.conf
```

2. Asegúrate de que el bloque del proyecto esté así:

```apache
<Directory "D:/laragon/www">
    Options Indexes FollowSymLinks Includes ExecCGI
    AllowOverride All
    Require all granted
</Directory>
```

3. Reiniciar Apache desde el panel de Laragon o ejecutar:

```bash
httpd -k restart
```

4. Confirmar que las URLs limpias funcionan accediendo a:

```text
http://localhost/simple_dashboard_php/pages/examples/projects
```

### ✅ XAMPP (Windows)

1. Abrir el archivo de configuración de Apache:

```text
C:\xampp\apache\conf\httpd.conf
```

2. Buscar el bloque:

```apache
<Directory "C:/xampp/htdocs">
    Options Indexes FollowSymLinks Includes ExecCGI
    AllowOverride None
    Require all granted
</Directory>
```

3. Cambiarlo a:

```apache
<Directory "C:/xampp/htdocs">
    Options Indexes FollowSymLinks Includes ExecCGI
    AllowOverride All
    Require all granted
</Directory>
```

4. Guardar y reiniciar Apache desde el Panel de Control de XAMPP o por consola:

```bash
httpd -k restart
```

5. Verificar acceso:

```text
http://localhost/simple_dashboard_php/pages/examples/projects
```

**🧠 Nota:**
También puedes crear un bloque `<Directory>` específico:

```apache
<Directory "C:/xampp/htdocs/simple_dashboard_php/pages/examples/projects">
    AllowOverride All
    Require all granted
</Directory>
```

### ✅ Apache (Linux / Configuración manual)

1. Habilitar el módulo `mod_rewrite`:

```bash
sudo a2enmod rewrite
```

2. Editar la configuración del sitio (por ejemplo `/etc/apache2/sites-available/000-default.conf`):

```apache
<Directory /var/www/html/simple_dashboard_php>
    AllowOverride All
    Require all granted
</Directory>
```

3. Guardar y reiniciar Apache:

```bash
sudo systemctl restart apache2
```

4. Verificar:

```bash
curl -I http://localhost/simple_dashboard_php/pages/examples/projects/
```

### ✅ cPanel / Hosting compartido

En cPanel no tienes acceso al `httpd.conf`, pero los servidores suelen tener `mod_rewrite` habilitado por defecto.

Solo asegúrate de:

1. Subir el archivo `.htaccess` junto al `index.php` del módulo.
2. Si usas un subdominio o dominio apuntando a esa carpeta, no se requieren cambios adicionales.
3. Si las URLs limpias no funcionan, contacta al soporte del hosting y pide que el dominio permita `AllowOverride All` en tu carpeta.

**🧩 Ejemplo de estructura en cPanel:**

```text
/home/usuario/public_html/simple_dashboard_php/pages/examples/projects/.htaccess
```

### ✅ Nginx

Nginx **no usa** `.htaccess`, por lo que la configuración se hace directamente en el archivo del sitio.

1. Abrir o crear el archivo del sitio:

```text
/etc/nginx/sites-available/simple_dashboard_php.conf
```

2. Agregar una regla de reescritura equivalente:

```ngnix
server {
    listen 80;
    server_name localhost;

    root /var/www/html/simple_dashboard_php/pages/examples/projects;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
    }
}
```

3. Habilitar el sitio y recargar Nginx:

```bash
sudo ln -s /etc/nginx/sites-available/simple_dashboard_php.conf /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```
