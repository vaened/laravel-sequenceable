# Laravel Sequenceable Package
Este paquete provee funcionalidad para la creación de secuencias en base de datos.
## Cómo instalar
1. Se recomienda instalar este paquete a través de composer.
```sh
$ composer require enea/laravel-sequenceable
```
2. Agregue el provider a la llave `providers` en `config/app.php`.
```php
'providers' => [
    // ...
    Enea\Sequenceable\Providers\SequenceableServiceProvider::class,
    // ...
],
```
3. Publique los archivos de configuración.
```sh
$ php artisan vendor:publish --provider='Enea\Sequenceable\Providers\SequenceableServiceProvider'
```
4. Ejecute la migración de la tabla para las secuencias.
```sh
$ php artisan migrate
```

## Uso Básico
Para empezar con el paquete es tan sencillo como usar el trait `Sequenceable` e implementar la interfaz `SequenceableContract`, luego de esto sólo debe especificar que campos se autoincrementarán
```php
<?php namespace App;

use Enea\Sequenceable\Contracts\SequenceableContract;
use Enea\Sequenceable\Sequenceable;
use Illuminate\Database\Eloquent\Model;

class Document extends Model implements SequenceableContract
{

    use Sequenceable;

    /**
     * Returns the configuration of the sequences
     *
     * @return array
     */
    public function sequencesSetup(): array
    {
        return [
            'number'
        ];
    }
}
```

## Configuración
Las secuencias se puede configurar de varias maneras.
1. La forma más sencilla.
```php
    public function sequencesSetup(): array
    {
        return [ 'number' ];
    }
```
2. Con una clave personalizada.
```php
    public function sequencesSetup(): array
    {
        return [ 
            'custom_key' => [
                'number' 
            ]
        ];
    }
```
2. Con una clave dinámica, esto generara secuencias dependiendo del origen.
```php
    public function sequencesSetup(): array
    {
        return [ 
            $this->makeType() => [
                'number' 
            ]
        ];
    }

    protected function makeType()
    {
        return $this->origin;
    }
```
4. Todo puede encapsularse en un modelo de secuencia personalizado.
```php
    public function sequencesSetup(): array
    {
        return [ 
            CustomSequence::class => [
                // sequences
            ]
        ];
    }
```
## Facade
Existe un `Facade` para facilitar la obtención de las secuencias que pertenecen a un modelo.
```php
Succession::on(Document::class);
```
Esto devuelve una instancia de `Illuminate\Support\Collection` dónde cada llave es representada por el `column_key` definido en la configuración del modelo, y el valor es una instancia del modelo de secuencia al que pertenece.

## Personalización
El paquete cuenta con una configuración que genera las secuencias con la siguiente estructura.

 id       | sequence | source    | column_key | description      | created_at          | updated_at          
----------|----------|-----------|------------|------------------|---------------------|---------------------
 37cc068a |        1 | documents | number     | documents.number | 2017-06-29 18:40:44 | 2017-06-29 18:40:44 

Puede revisar el modelo [Sequence](https://github.com/eneasdh-fs/laravel-sequenceable/blob/master/src/Model/Sequence.php) que viene configurado en el paquete para conocer más sobre su funcionamiento.
#### Estructura Base
Puedes implementar la interfaz `SequenceContract` o extender del modelo `Sequence` para modificar el comportamiento que creas conveniente.

 campo          | Descripción
----------------|------------------------------------------------------------------------------------
 **id**         | se genera en base al concatenado de la tabla, la columna y la clave.
**sequence**    | almacena la secuencia actual del campo.
**source**      | contiene el nombre de la tabla.
**column_key**  | concatenado del nombre de la columna y la clave.
**description** | contiene la descripción de la secuencia.
**created_at**  | indica la fecha y hora de creación de la secuencia.
**updated_at**  | indica la ultima vez que la secuencia se actualizo.

#### Modelo
Puede cambiar el modelo de secuencia predeterminado desde `config\sequenceable.php` en la llave `model`.
```php
<?php
return [
    /*
    |--------------------------------------------------------------------------
    | Sequence Model
    |--------------------------------------------------------------------------
    |
    | This key defines the base sequence model that will be used to generate the autoincrementable 
    | values, you can modify this key and define your own sequence model whenever 
    | you implement the sequenceContract interface or extend the base model
    */
   'model' => \Enea\Sequenceable\Model\Sequence::class,
];
```
o especificar de forma explicita que modelo quieres usar con determinados campos, esto lo puedes lograr en la configuración de las secuencias de tu modelo.
```php
    public function sequencesSetup(): array
    {
        return [ 
            CustomSequence::class => [
                // sequences
            ]
        ];
    }
```
#### Tabla de almacenamiento
Si ya cuenta con una tabla que almacena las secuencias en su base de datos, probablemente será necesario un modelo de secuencia deferente del que ya viene configurado. 

Existen algunos campos de deberá almacenar en su tabla de secuencias tales como:
1. La clave de la secuencia.
2. El concatenado de la columna más la clave, separados por un punto.
3. El nombre de la tabla a la que pertenece la secuencia.

Puede ver un ejemplo de esto en la carpeta de `test` y buscando los archivos `Models\CustomSequence.php` y `Migrations/2017_04_23_200525_create_custom_sequences_table.php`

## Más documentación
Se puede encontrar gran cantidad de comentarios dentro del código fuente al igual que en las pruebas ubicadas en el directorio `tests`.
