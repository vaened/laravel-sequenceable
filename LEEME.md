# Laravel Sequenceable Package
[![Build Status](https://travis-ci.org/eneasdh-fs/laravel-sequenceable.svg?branch=master)](https://travis-ci.org/eneasdh-fs/laravel-sequenceable) [![StyleCI](https://styleci.io/repos/94660091/shield?branch=master)](https://styleci.io/repos/94660091)

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
Para empezar con el paquete es tan sencillo como usar el trait `Sequenceable` e implementar la interfaz `SequenceableContract`, luego de esto solo debe especificar que campos se autoincrementarán

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
    public function sequencesSetup()
    {
        return ['number'];
    }
}
```

## Configuración
Se pueden configurar las secuencias de 5 posibles formas, cada una sigue una estructura logica además de poder ser combinadas entre sí.
- ##### Simple.
    Esta es la forma más sencilla, solo debe definir las columnas que necesite en el arreglo configuración.

```php
    public function sequencesSetup()
    {
        return [ 'number' ];
    }
```
- ##### Clave personalizada.
    Se puede establecer una clave personalizada para la secuencia usando la siguiente sintaxis.

```php
    public function sequencesSetup()
    {
        return [ 
            'custom_key' => [
                'number' 
            ]
        ];
    }
```
- ##### Clave dinámica.
    De esta manera puede generar secuencias independientes según la clave, en este ejemplo se puede ver cómo se establece que la clave se construirá en base al tipo de documento, esto generar tantas secuencias como tipos de documentos existan. Puede ver un ejemplo de esto en el test `a_sequence_with_a_dynamic_value_is_generated` ubicado en el archivo [`GenerateSequenceTest`](https://github.com/eneasdh-fs/laravel-sequenceable/blob/master/tests/GenerateSequenceTest.php)

```php
    public function sequencesSetup()
    {
        return [ 
            $this->sequenceKey() => [
                'number' 
            ]
        ];
    }

    protected function sequenceKey()
    {
        return $this->type;
    }
```

- ##### Longitud fija.
    Establecer una longitud a la secuencia será de ayuda cuando necesite darle un formato de presentación, para que esto funcione deberá llamar a la columna anteponiendo un `prefijo` que se establece en el archivo de configuración `config\sequenceable.php` en la llave `prefix`.
```php
    public function sequencesSetup()
    {
        return [
            'number' => 8,
        ];
    }
```

- ##### Modelo personalizado.
    Puede encapsular las secuencias en un modelo personalizado, este modelo de secuencia deberá estar configurado para que pueda persistir las secuencias en la base de datos. Esto se muestra en la parte inferior de la documentación.
```php
    public function sequencesSetup()
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

Puede revisar el modelo [`Sequence`](https://github.com/eneasdh-fs/laravel-sequenceable/blob/master/src/Model/Sequence.php) que viene configurado en el paquete para conocer más sobre su funcionamiento.
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
o especificar de forma explicita que modelo desea usar con determinados campos, esto lo puedes lograr en la configuración de las secuencias de su modelo.
```php
    public function sequencesSetup()
    {
        return [ 
            CustomSequence::class => [
                // sequences
            ]
        ];
    }
```
#### Almacenamiento
En caso de ser necesario puede definir cómo se almacenarán las secuencias en su base de datos desde `config\sequenceable.php` en la llave `autoffilling`, de esta manera las secuencias se rellenarán con la longitud que haya establecido en la configuración del modelo al momento de ser persistidas en la base de datos.
```php
<?php
return [
     /*
     |---------------------------------------------------------------------------
     | Auto filling
     |---------------------------------------------------------------------------
     | This option allows you to automatically fill the fields in your database, as specified in 
     | the model configuration. If you want to customize the value with which to autofill, 
     | you must change this key by replacing the value with the  character you want, 
     | by default, if it is true, it will autocomplete to zero
     |
     */

    'autofilling' => false,
];
```
#### Prefijo
En caso de no querer almacenar las secuencias con una longitud fija en su base de datos, pero aún desea presentarlas con un formato, entonces simplemente puede dejar `autofilling` en falso y llamar a sus columnas anteponiendo un prefijo.

```php
<?php
return [
    /*
     |---------------------------------------------------------------------------
     | Prefix
     |---------------------------------------------------------------------------
     | This key specifies the prefix that is used when you need to display the sequence with the 
     | number of characters defined in the configuration, if you do not want to use any 
     | prefix, set this key to null
     */

    'prefix' => 'full_'
];
```

Configurando la secuencia con una [`longitud fija`](#longitud-fija), la llamada se haría concatenando `prefijo + columna`, en este caso `full_number`, esto devolverá la secuencia que pertenece a la columna `number` que será rellenado con `ceros` a la izquierda hasta alcanzar la longitud configurada en el modelo.

```php
    public function store()
    {
        $document = new Document();
        $document->save();
        
        // number = 1
        // full_number = 00000001
        
        return view('document.show', [
            'document_number' => $document->full_number
        ]);
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
