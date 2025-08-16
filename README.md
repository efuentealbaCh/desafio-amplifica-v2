
# Mi tienda en Shopify - Desafio Amplifica

Este repositorio contiene la solución para la prueba técnica de **Amplifica**, que consiste en el desarrollo de una aplicación backend utilizando Laravel para integrar API's externas de plataformas e-commerce. El objetivo es consumir estas API's de terceros, manejar credenciales de forma segura y construir soluciones escalables y robustas.

## Requisitos

Antes de comenzar, asegúrate de tener las siguientes herramientas instaladas en tu máquina:

- [Git](https://git-scm.com/)
- [Composer](https://getcomposer.org/)
- [Node & npm](https://docs.npmjs.com/downloading-and-installing-node-js-and-npm)

Además, este proyecto está desarrollado con las siguientes versiones de las herramientas:

- Laravel 12
- PHP 8.2^
- Node 20.10^

## Primeros pasos
### Descarga de repositorio
1. **Clonar el repositorio**  
   Ejecuta el siguiente comando para clonar el repositorio en tu máquina local:

   ```bash
   git clone https://github.com/efuentealbaCh/desafio-amplifica-v2.git
   ```
2. **Accder al directorio**  
    Ejecutar el comando
    ```bash
    cd desafio-amplifica-v2
    ```
### Instlación de dependencias y preparación de entorno
1. **Instalación de paquetes**

    ```bash
    npm install
    composer install
    ```
2. **Nota**
    
    Solo en caso de ser necesario correr en comando
    ```bash
    npm run build
    ```
    para asegurar que la aplicación se construya correctamente.
## Ejecución de programa
### Consideraciones importantes
1. **Usuarios**

    Todo lo relacionado con la parte de administración de usuarios y la parte visual del programa son gracias al uso de Breeze, que como bien menciono se encarga de toda la parte de gestion de usuarios.

2. **Base de datos**

    Se usa por defecto sqlite para gestionar la parte de una base de datos en local, la que permite ademas la correcta interación con Breeze, además se deja por defecto un usurio de prueba para poder acceder al sitio.

    ```code
    email: tester.user@gmail.com
    password: 123456789
    ```
3. **Pedidos**

    No hay registro de pedidos por tanto he de revisar la logica con la que se hace la petición.

4. **Pruebas Unitarias**

    Existe un registro de pruebas unitarias que fue generado por la libreria Breeze. Por tanto al ejecutar el comando de pruebas unitarias puede que aparesca que se ejecutaraon muchos testeos en contraste a los que se mencionan en la sección de pruebas unitarias.

5. **env**

    Si desea probar en su totalida requiere el archivo con las credenciales, contactar con el desarrollador por via email: efuentealba038@gmail.com, para solicitar una copia de .env.

### Comandos de apertura
1. **Abrir teminal**
    
    Es necesario abrir dos pestañas de terminal para ejecutar por completo el código.
    En un terminal ejecutamos 
    ```bash 
        php artisan serve
    ```
    En la segunda terminal ejecutamos
    ```bash 
        npm run dev
    ```
2. **Aceder al sitio web**

    En su estado rais podemos acceder al sitio web utilizando la ruta
    ```bash
    http://127.0.0.1:8000
    ```

    Debe de hacer click en los botón ya sea para ingresar al registro de nuevos  usuarios o para hacer login con el usuario de prueba ya existente mencionado con anterioridad.
3. **Login y registro**
    Se puede acceder a las rutas por medio de los botones o usando la URL correspondiente

    Para el login sería:
    ```bash
    http://127.0.0.1:8000/login
    ```

    Para regitrar un nuevo usuario:

    ```bash
    http://127.0.0.1:8000/register
    ```
### Probando el sistema

**Nota:**
     Dentro del sistema se puede probar navegando a travez de las barrar de navegación, cuando se concreta el el login, se nos redirecciona a la ventana de Producctos, se puede navegar usando la barra superio (navbar) para ver las diferentes funcionalidades solicitadas en la prueba.Lo importante acá es como hacemos funcionar esto. Para ello cabe recalcar que toda la logica de extración de datos esta almacenada en el archivo ShopifyService.php que esta en la ruta app/Services/ShopifyService.php
1. **Metodos importantes**
    
    - __constructor, este metodo se encarga de generar la intacia de cliente para poder hacer la petición inicial a la tienda en Shopify. Entre algunas de las cosas relevantes tenemos el manejo del dominio y las secret_key de mi tienda, asi como un tiempo de espera máximo para la respuesta de la app

    - makeRequest, con este metodo nos encargamos de realizar las peticiones hacia la api de shopify ya sea para la parte de los productos como de los pedidos.

2. **Vista de productos, pedidos y dashboard**

    Cabe recalcar que tanto la logica de los productos y pedidos funcionan de manera similar, para este caso se considera la parte de visualizar y exportar los datos de acuerdo a lo visualizado.

    El dashboard cuenta con una un buscador por fecha el cual cuenta filtra los pedidos para poder ver los gráficos iniciales. Los que corresponden a pedidos del mensuales y a los pedidos mas vendidos.

## Pruebas unitarias


1. **Ejecucion de pruebas**
    
    - Usar el comando (En linux se ejuta de esta forma)
     
        ```bash
        ./vendor/bin/phpunit
        ```

   - En caso de querer ejecutar las pruebas por archivo puede usar los siguientes comandos
    
        ```bash
        ./vendor/bin/phpunit tests/Unit/Services/ShopifyServiceTest.php
        ```
        ```bash
        ./vendor/bin/phpunit tests/Unit/Exports/ProductsExportTest.php
        ```
        ```bash
        ./vendor/bin/phpunit tests/Unit/Services/ShopifyServiceTest.php
        ```

    - En caso de querer probar metodo unicamente utilizar el comando

        ```bash
        ./vendor/bin/phpunit --filter nombre_del_metodo
        ``` 
        Ejemplo

        ```bash
        ./vendor/bin/phpunit --filter test_index_displays_products_from_shopify
        ```
