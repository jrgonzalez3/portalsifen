# Instrucciones para Configurar Nginx con Múltiples Proyectos

## Objetivo
Acceder a: `http://10.99.99.56:8080/portalsifen/admin`

## Pasos

### 1. Ubicar el archivo de configuración principal de Nginx

En Laragon, el archivo principal suele estar en:
```
c:\laragon\etc\nginx\nginx.conf
```

O busca el archivo de configuración del servidor en puerto 8080:
```
c:\laragon\etc\nginx\sites-enabled\
```

### 2. Agregar la configuración de portalsifen

Abre el archivo de configuración del servidor que escucha en el puerto 8080 y agrega este bloque **DENTRO** del bloque `server { }`:

```nginx
location /portalsifen {
    alias c:/laragon/www/portalsifen/public;
    index index.php index.html;
    
    try_files $uri $uri/ @portalsifen;
    
    location ~ \.php$ {
        fastcgi_pass   127.0.0.1:9000;
        fastcgi_index  index.php;
        fastcgi_param  SCRIPT_FILENAME  $request_filename;
        fastcgi_param  QUERY_STRING       $query_string;
        fastcgi_param  REQUEST_METHOD     $request_method;
        fastcgi_param  CONTENT_TYPE       $content_type;
        fastcgi_param  CONTENT_LENGTH     $content_length;
        fastcgi_param  SCRIPT_NAME        $fastcgi_script_name;
        fastcgi_param  REQUEST_URI        $request_uri;
        fastcgi_param  DOCUMENT_URI       $document_uri;
        fastcgi_param  DOCUMENT_ROOT      $realpath_root;
        fastcgi_param  SERVER_PROTOCOL    $server_protocol;
        fastcgi_param  GATEWAY_INTERFACE  CGI/1.1;
        fastcgi_param  SERVER_SOFTWARE    nginx/$nginx_version;
        fastcgi_param  REMOTE_ADDR        $remote_addr;
        fastcgi_param  REMOTE_PORT        $remote_port;
        fastcgi_param  SERVER_ADDR        $server_addr;
        fastcgi_param  SERVER_PORT        $server_port;
        fastcgi_param  SERVER_NAME        $server_name;
        fastcgi_param  REDIRECT_STATUS    200;
    }
}

location @portalsifen {
    rewrite /portalsifen/(.*)$ /portalsifen/index.php?/$1 last;
}
```

### 3. Reiniciar Nginx

En Laragon:
- Click derecho en el ícono → Nginx → Reload

O desde línea de comandos:
```bash
c:\laragon\bin\nginx\nginx.exe -s reload
```

### 4. Acceder al panel

Ahora podrás acceder con:
- `http://localhost:8080/portalsifen/admin`
- `http://10.99.99.56:8080/portalsifen/admin`

## Solución de Problemas

### Bad Gateway (502)

Si obtienes "bad gateway":

1. **Verificar que PHP-FPM esté corriendo**
   - En Laragon, asegúrate de que PHP esté iniciado
   - Verifica el puerto FastCGI (por defecto 9000)

2. **Verificar logs de Nginx**
   ```
   c:\laragon\etc\nginx\logs\error.log
   ```

3. **Verificar logs de PHP**
   ```
   c:\laragon\bin\php\php-8.3.28\logs\
   ```

### Archivo no encontrado

Si obtienes "File not found":

1. Verifica que la ruta `alias` sea correcta
2. Asegúrate de que `index.php` exista en `c:/laragon/www/portalsifen/public/`

### Permisos

Si hay problemas de permisos:
```bash
icacls "c:\laragon\www\portalsifen" /grant Everyone:F /T
```

## Ejemplo de Configuración Completa

Así debería verse tu archivo de configuración:

```nginx
server {
    listen 8080;
    server_name localhost 10.99.99.56;
    
    root c:/laragon/www;
    index index.php index.html;
    
    # Otros proyectos pueden estar aquí
    
    # Configuración para portalsifen
    location /portalsifen {
        alias c:/laragon/www/portalsifen/public;
        index index.php index.html;
        
        try_files $uri $uri/ @portalsifen;
        
        location ~ \.php$ {
            fastcgi_pass   127.0.0.1:9000;
            fastcgi_index  index.php;
            fastcgi_param  SCRIPT_FILENAME  $request_filename;
            include        fastcgi_params;
        }
    }
    
    location @portalsifen {
        rewrite /portalsifen/(.*)$ /portalsifen/index.php?/$1 last;
    }
    
    # Configuración general para otros archivos PHP
    location ~ \.php$ {
        fastcgi_pass   127.0.0.1:9000;
        fastcgi_index  index.php;
        fastcgi_param  SCRIPT_FILENAME  $document_root$fastcgi_script_name;
        include        fastcgi_params;
    }
}
```
