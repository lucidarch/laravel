## Lucid

The Lucid Architecture is a software architecture that consolidates code-base maintenance as the application scales,
from becoming overwhelming to handle, gets us rid of rotting code that will later become legacy code, and translate
the day-to-day language such as Feature and Service into actual, physical code.

Read more about the [Lucid Architecture Concept](https://medium.com/vine-lab/the-lucid-architecture-concept-ad8e9ed0258f).

If you prefer a video, watch the announcement of The Lucid Architecture at LaraconEU 2016:

##### The Lucid Architecture for Building Scalable Applications - Laracon EU 2016
[![Abed Halawi - The Lucid Architecture for Building Scalable Applications](http://img.youtube.com/vi/wSnM4JkyxPw/0.jpg)](http://www.youtube.com/watch?v=wSnM4JkyxPw "Abed Halawi - The Lucid Architecture for Building Scalable Applications")


### Join The Community on Slack
[![Slack Status](https://lucid-slack.herokuapp.com/badge.svg)](https://lucid-slack.herokuapp.com)

## Index
- [Installation](#installation)
- [Introduction](#introduction)
    - [Components](#components)
        - [Service](#service)
        - [Feature](#feature)
        - [Job](#job)
    - [Data](#data)
    - [Foundation](#foundation)
- [Getting Started](#getting-started)
- [Microservices](#microservices)

## Installation

### 7.x
To start your project with Lucid right away, run the following command:

```
composer create-project lucid-arch/laravel my-project
```

This will give you a Laravel 7 installation with Lucid out-of-the-box. If you wish to download other versions of Laravel you may specify it as well:

##### 6.x
```
composer create-project lucid-arch/laravel=6.x my-project-6.x
```

##### 5.5
```
composer create-project lucid-arch/laravel=5.5.x my-project-5.5
```

## Introduction

### Directory Structure
```
src
├── Data
├── Domains
    └── * domain name *
            ├── Jobs
├── Foundation
└── Services
    └── * service name *
        ├── Console
        ├── Features
        ├── Http
        ├── Providers
        ├── Tests
        ├── database
        └── resources
```

### Components
| Component | Path | Description |
|---------|--------| ----------- |
| Service | src/Service/[service] | Place for the [Services](#service) |
| Feature | src/Services/[service]/Features/[feature] | Place for the [Features](#feature) of [Services](#service) |
| Job | src/Domains/[domain]/Jobs/[job] | Place for the [Jobs](#job) that expose the functionalities of Domains |
| Data | src/Data | Place for models, repositories, value objects and anything data-related |
| Foundation | src/Foundation | Place for foundational (abstract) elements used across the application |


#### Service
Each part of an application is a service (i.e. Api, Web, Backend). Typically, each of these will have their way of
handling and responding to requests, implementing different features of our application, hence, each of them will have
their own routes, controllers, features and operations. A Service will seem like a sub-installation of a Laravel
application, though it is just a logical grouping than anything else.

To better understand the concept behind Services, think of the terminology as:
"Our application exposes data through an Api service", "You can manipulate and manage the data through the Backend service".

One other perk of using Lucid is that it makes the transition process to a Microservices architecture simpler,
when the application requires it. See [Microservices](#microservices).

##### Service Directory Structure

Imagine we have generated a service called **Api**, can be done using the `lucid` cli by running:

> You might want to [Setup](#setup) to be able to use the `lucid` command.

```
lucid make:service api
```

We will get the following directory structure:

```
src
└── Services
    └── Api
        ├── Console
        ├── Features
        ├── Http
        │   ├── Controllers
        │   ├── Middleware
        │   ├── Requests
        │   └── routes.php
        ├── Providers
        │   ├── ApiServiceProvider.php
        │   └── RouteServiceProvider.php
        ├── Tests
        │   └── Features
        ├── database
        │   ├── migrations
        │   └── seeds
        └── resources
            ├── lang
            └── views
                └── welcome.blade.php
```

#### Feature

A Feature is exactly what a feature is in our application (think Login feature, Search for hotel feature, etc.) as a class.
Features are what the Services' controllers will serve, so our controllers will end up having only one line in our methods, hence,
the thinnest controllers ever! Here's an example of generating a feature and serving it through a controller:

> You might want to [Setup](#setup) to be able to use the `lucid` command.

> IMPORTANT! You need at least one service to be able to host your features. In this example we are using the Api
service generated previously, referred to as `api` in the commands.

```
lucid make:feature SearchUsers api
```

And we will have `src/Services/Api/Features/SearchUsersFeature.php` and its test `src/Services/Api/Tests/Features/SearchUsersFeatureTest.php`.

Inside the Feature class, there's a `handle` method which is the method that will be called when we dispatch that feature,
and it supports dependency injection, which is the perfect place to define your dependencies.

Now we need a controller to serve that feature:
```
lucid make:controller user api
```

And our `UserController` is in `src/Services/Api/Http/Controllers/UserController.php` and to serve that feature,
in a controller method we need to call its `serve` method as such:

```
namespace App\Services\Api\Http\Controllers;

use App\Services\Api\Features\SearchUsersFeature;

class UserController extends Controller
{
    public function index()
    {
        return $this->serve(SearchUsersFeature::class);
    }
}
```
#### Views
To access a service's view file, prepend the file's name with the service name followed by two colons `::`

Example extending view file in blade:
```
@extends('servicename::index')
```

Usage with jobs is similar:
```php
new RespondWithViewJob('servicename::user.login')
```

`RespondWithJsonJob` accepts the following parameters:
```php
RespondWithViewJob($template, $data = [], $status = 200, array $headers = []);
```

Usage of template with data:
```php
$this->run(new RespondWithViewJob('servicename::user.list', ['users' => $users]));
```
Or
```php
$this->run(RespondWithViewJob::class, [
    'template' => 'servicename::user.list',
    'data' => [
        'users' => $users
    ],
]);
```

#### Job
A Job is responsible for one element of execution in the application, and play the role of a step in the accomplishment
of a feature. They are the stewards of reusability in our code.

Jobs live inside Domains, which requires them to be abstract, isolated and independent from any other job be it
in the same domain or another - whatever the case, no Job should dispatch another Job.

They can be ran by any Feature from any Service, and it is the *only way* of communication between services and domains.

*Example:* Our `SearchUsersFeature` has to perform the following steps:

- Validate user search query
- Log the query somewhere we can look at later
- If results were found
    - Log the results for later reference (async)
    - Increment the number of searches on the found elements (async)
    - Return results
- If no results were found
    - Log the query in a "high priority" log so that it can be given more attention

Each of these steps will have a job in its name, implementing only that step. They must be generated in their corresponding
domains that they implement the functionality of, i.e. our `ValidateUserSearchQueryJob` has to do with user input,
hence it should be in the `User` domain. While logging has nothing to do with users and might be used in several other
places so it gets a domain of its own and we generate the `LogSearchResultsJob` in that `Log` domain.

To generate a Job, use the `make:job <job> <domain>` command:

```
lucid make:job SearchUsersByName user
```

Similar to Features, Jobs also implement the `handle` method that gets its dependencies resolved, but sometimes
we might want to pass in input that doesn't necessarily have to do with dependencies, those are the params of the job's
constructor, specified here in `src/Domains/User/Jobs/SearchUsersByNameJob.php`:

```php
namespace App\Domains\User\Jobs;

use Lucid\Foundation\Job;

class SearchUsersByNameJob extends Job
{
    private $query;
    private $limit;

    public function __construct($query, $limit = 25)
    {
        $this->query = $query;
        $this->limit = $limit;
    }

    public function handle(User $user)
    {
        return $user->where('name', $this->query)->take($this->limit)->get();
    }
}
```

Now we need to run this and the rest of the steps we mentioned in `SearchUsersFeature::handle`:

```php
public function handle(Request $request)
{
    // validate input - if not valid the validator should
    // throw an exception of InvalidArgumentException
    $this->run(new ValidateUserSearchQueryJob($request->input()));

    $results = $this->run(SearchUsersJob::class, [
        'query' => $request->input('query'),
    ]);

    if (empty($results)) {
        $this->run(LogEmptySearchResultsJob::class, [
            'date' => new DateTime(),
            'query' => $request->query(),
        ]);

        $response = $this->run(new RespondWithJsonErrorJob('No users found'));
    } else {
        // this job is queueable so it will automatically get queued
        // and dispatched later.
        $this->run(LogUserSearchJob::class, [
            'date' => new DateTime(),
            'query' => $request->input(),
            'results' => $results->lists('id'), // only the ids of the results are required
        ]);

        $response = $this->run(new RespondWithJsonJob($results));
    }

    return $response;
}
```

As you can see, the sequence of steps is clearly readable with the least effort possible when following each `$this->run`
call, and the signatures of each Job are easy to understand.

A few things to note regarding the implementation above:

- There is no difference between running a job by instantiating it like `$this->run(new SomeJob)` and
passing its class name `$this->run(SomeJob::class)`
it is simply a personal preference for readability and writing less code, when a Job takes only one parameter
it's instantiated.
- The order of parameters that we use when calling a job with its class name is irrelevant to their order in the
Job's constructor signature. i.e.
```php
$this->run(LogUserSearchJob::class, [
    'date' => new DateTime(),
    'query' => $request->input(),
    'resultIds' => $results->lists('id'), // only the ids of the results are required
]);
```
```php
class LogUserSearchJob
{
    public function __construct($query, array $resultIds, DateTime $date)
    {
        // ...
    }
}
```
This will work perfectly fine, as long as the key name (`'resultIds' => ...`) is the same as the variable's name in the constructor (`$resultIds`)
- Of course, we need to create and import (`use`) our Job classes with the correct namespaces, but we won't do that here
since this is only to showcase and not intended to be running, for a working example see [Getting Started](#getting-started).

## Data
Data is not really a component, more like a directory for all your data-related classes such as Models, Repositories,
Value Objects and anything that has to do with data (algorithms etc.).

## Foundation
This is a place for foundational elements that act as the most abstract classes that do not belong in any of the
components, currently holds the `ServiceProvider` which is the link between the services and the framework (Laravel).
You might never need or use this directory for anything else, but in case you encountered a case where a class
needs to be shared across all components and does belong in any, feel free to use this one.

Every service must be registered inside the foundation's service provider after being created for Laravel to know about it,
simply add `$this->app->register([service name]ServiceProvider::class);` to the `register` methods of the
foundation's `ServiceProvider`. For example, with an Api Service:

```php
// ...
use App\Services\Api\Providers\ApiServiceProvider;
// ...
public function register()
{
    $this->app->register(ApiServiceProvider::class);
}
```

## Getting Started
This project ships with the [Lucid Console](https://github.com/lucid-architecture/laravel-console) which provides an interactive
user interface and a command line interface that are useful for scaffolding and exploring Services, Features, and Jobs.

### Setup
The `lucid` executable will be in `vendor/bin`. If you don't have `./vendor/bin/` as part of your `PATH` you will
need to execute it using `./vendor/bin/lucid`, otherwise add it with the following command to be able to simply
call `lucid`:

```
export PATH="./vendor/bin:$PATH"
```

For a list of all the commands that are available run `lucid` or see the [CLI Reference](https://github.com/lucid-architecture/laravel-console).

#### Launching the Interactive Console (UI)

1. Serve Application
One way is to use the built-in server by running:
```bash
php artisan serve
```
> Any other method would also work (Apache, Nginx, etc...)
2. Run `php artisan vendor:publish --provider="Lucid\Console\LucidServiceProvider"`
3. Visit your application at */lucid/dashboard*

### 1. Create a Service

##### CLI
```
lucid make:service Api
```

##### UI



Using one of the methods above, a new service folder must've been created under `src/Services` with the name `Api`.

The **Api** directory will initially contain the following directories:

```
src/Services/Api
├── Console         # Everything that has to do with the Console (i.e. Commands)
├── Features        # Contains the Api's Features classes
├── Http            # Routes, controllers and middlewares
├── Providers       # Service providers and binding
├── database        # Database migrations and seeders
└── resources       # Assets, Lang and Views
```

One more step is required for Laravel to recognize the service we just created.

#### Register Service

- Open `src/Foundation/Providers/ServiceProvider`
- Add `use App\Services\Api\Providers\ApiServiceProvider`
- In the `register` method add `$this->app->register(ApiServiceProvider::class)`

### 2. Create a Feature

##### CLI
```
lucid make:feature ListUsers api
```

##### UI

Using one of the methods above, the new Feature can be found at `src/Services/Api/Features/ListUsersFeature.php`.
Now you can fill up a bunch of jobs in its `handle` method.

### 3. Create a Job
This project ships with a couple of jobs that can be found in their corresponding domains under `src/Domains`

##### CLI
```
lucid make:job GetUsers user
```

##### UI

Using one of the methods above, the new Job can be found at `src/Domains/User/Jobs/GetUsers` and now you can fill
it with functionality in the `handle` method. For this example we will just add a static `return` statement:

```php
public function handle()
{
    return [
        ['name' => 'John Doe'],
        ['name' => 'Jane Doe'],
        ['name' => 'Tommy Atkins'],
    ];
}
```

### 4. All Together
Back to the Feature we generated earlier, add `$this->run(GetUsersJob)` (remember to `use` the job with the correct
namespace `App\Domains\User\Jobs\GetUsersJob`).

##### Run The Job
In **ListUsersFeature::handle(Request $request)**

```php
public function handle(Request $request)
{
    $users = $this->run(GetUsersJob::class);

    return $this->run(new RespondWithJsonJob($users));
}
```

The `RespondWithJsonJob` is one of the Jobs that were shipped with this project, it lives in the `Http` domain and is
used to respond to a request in a structured JSON format.

##### Serve The Feature
To be able to serve that Feature we need to create a route and a controller that does so.

Generate a plain controller with the following command

```
lucid make:controller user api --plain
```

Add the `get` method to it:

```php
class UserController extends Controller
{
    public function get()
    {
        return $this->serve(ListUsersFeature::class);
    }
}
```

We just need to create a route that would delegate the request to our `get` method:

In `src/Services/Api/Http/routes.php` you will find the route group `Route::group(['prefix' => 'api'], function() {...`
Add the `/users` route within that group.

```php
Route::get('/users', 'UserController@get');
```

Now if you visit `/api/users` you should see the JSON structure.

## Microservices
If you have been hearing about microservices lately, and wondering how that works and would like to plan your next project
based on microservices, or build your application armed and ready for the shift when it occurs, Lucid is your best bet.
It has been designed with scale at the core and the microservice transition in mind, it is no coincidence that the
different parts of the application that will (most probably) later on become the different services with the microservice
architecture are called **Service**. However, [it is recommended](http://martinfowler.com/bliki/MonolithFirst.html)
that only when your monolith application grow so large that it becomes crucial to use microservices for the sake of
the progression and maintenance of the project, to do the shift; because once you've built your application using Lucid,
the transition to a microservice architecture will be logically simpler to plan and physically straight-forward
to implement. There is a [microservice counterpart](https://github.com/lucid-architecture/laravel-microservice)
to Lucid that you can check out [here](https://github.com/lucid-architecture/laravel-microservice).

With more on the means of transitioning from a monolith to a microservice.

### Event Hooks

Lucid exposes event hooks that allow you to listen on each dispatched feature, operation or job. This is especially useful for tracing:

```php
use Illuminate\Support\Facades\Event;
use Lucid\Foundation\Events\FeatureStarted;
use Lucid\Foundation\Events\OperationStarted;
use Lucid\Foundation\Events\JobStarted;

Event::listen(FeatureStarted::class, function (FeatureStarted $event) {
    // $event->name
    // $event->arguments
});

Event::listen(OperationStarted::class, function (OperationStarted $event) {
    // $event->name
    // $event->arguments
});

Event::listen(JobStarted::class, function (JobStarted $event) {
    // $event->name
    // $event->arguments
});
```
