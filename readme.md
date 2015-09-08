## Lucid

Read about the [Lucid Architecture Concept](https://medium.com/vine-lab/the-lucid-architecture-concept-ad8e9ed0258f).

### Installation
To start your project with a Lucid Structure right away, run the following:

```
composer create-project vinelab/lucid my-project
```

### Usage

#### Scaffolding Services
This project uses the [caffeinated\modules](https://github.com/caffeinated/modules)
package to manage Services (so called modules in the package terminology)

- Create a new service by running
```
php artisan module:make [ServiceName]
```

- Change `RouteServiceProvider`
A new service now exists in `src/Services/[ServiceName]` and the `RouteServiceProvider` class
should be in `src/Services/[ServiceName]/Providers/RouteServiceProvider`

    - Make it extend the *Foundation*'s *RouteServiceProvider* `App\Foundation\RouteServiceProvider`
    by replacing
    ```php
    use Caffeinated\Modules\Providers\RouteServiceProvider as ServiceProvider;
    ```
    with
    ```php
    use App\Foundation\RouteServiceProvider as ServiceProvider;
    ```
    - Remove useless `use` statement
    ```php
    use Illuminate\Routing\Router;
    ```
    - Replace the contents of the class with
    ```php
    class RouteServiceProvider extends ServiceProvider
    {
        protected $serviceName = 'ServiceName';
    }
    ```
    > this should be the service's directory name.
