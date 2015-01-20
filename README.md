#Lazy Objects

> This project is still in development phase. You should not use it before first stable release.

## 1. Why should you use lazy objects

Why should you use laze objects in your project?
Because domain is the most important thing in application and your job is to keep it clean.
Your professional responsibility is to keep your domain fair from the implementation details like
for example data storage.

Ok, but lets talk how lazy objects are going to help you with that.
Lets assume that you are working on new application for Github in php that use Github Api.

As you probably already know Github is based on community, without users it would be more or less just another hosting
for repositories. Every user have right to create as many repositories as he want (sometimes he needs to pay for them but
do not think about that now).

Lets create our User class first

```php
<?php

namespace Github;

class User
{
    private $login;

    private $publicRepositories;

    public function __construct(Login $login)
    {
        $this->login = $login;
        $this->publicRepositories = [];
    }

    public function getLogin()
    {
        return $this->login
    }

    public function hasPublicRepositories()
    {
        return (boolean) count($this->publicRepositories);
    }

    public function createNewPublicRepository(Repository\Name $name)
    {
        $this->publicRepositories[] = new Repository\PublicRepository($name, $this);
    }

    public function getPublicRepositories()
    {
        return $this->publicRepositories;
    }
}

```

And some basic public repository class that is not so important for our example.

```php
<?php

namespace Github\Repository;

class PublicRepository
{
    private $name;

    private $owner;

    public function __construct(Name $repoName, User $user)
    {
        $this->name = $repoName;
        $this->owner = $user;
    }
}

```

So above classes are our application domain. Both of them are just simple plain old php objects (POPO).
Now lets move to implementation part.
As you probably know Github have a quite nice API. After fast look at API documentation we know 2 things for sure.

1. Its possible to fetch all required by our domain user data from API (user login & user repositories).
2. User login and repositories are available under different endpoints in API.

So in order to get user we need to make following http call:
```
GET /users/:username
```

And in order to get user repositories we need to make next http call:
```
GET /users/:username/repos
```

What can we do? Of course we can add ```setPublicRepositories``` method to our User class and after constructing
user object execute it passing repositories fetched from the second endpoint. But...

1. As a Github user do you really can "set public repositories" that you own? **No**
2. Do we really need to know about all user public repositories each time when we create User object? **No**

This is where lazy objects are getting useful.
Whole idea is just to create a replacement for a method that will be executed instead of that method.
Wrapped User object also should still be an instance of User class (so our system could threat it like that).

## 2. How to create lazy  object

Our goal is to call GET repositories endpoint only when ``getPublicRepositories()`` method is executed on User object.
First we need to create a Replacement for that method.

```php
<?php

namespace Github\User\Proxy\Method;

use Coduo\LazyObjects\Proxy\Method\Replacement;

class GetPublicRepositories implements Replacement
{
    private $client;

    public function __construct(HttpClient $client)
    {
        $this->client = $client;
    }

    public function call(array $parameters, $object)
    {
        // $object - this is nothing more than User object instance that is "lazy"

        // 1. fetch json from api endpoint
        $repositoriesJson = $this->client->get(sprintf('/users/%s/repos', (string) $object->getLogin()));

        // 2. decode json
        $repositoriesData = json_decode($repositoriesJson, true);

        // 3. create public repositories from decoded data
        $repositories = [];
        foreach ($repositoriesData as $repositoryData) {
            $repositories[] = new PublicRepository($repositoryData['name'], $object);
        }

        // 4. return user repositories
        return $repositories;
    }
}
```

Now we need to create a proxy definition for our Github\User class, add it into the wrapper and use that wrapper to wrap User object

```php
<?php

use Coduo\LazyObjects\Proxy\Adapter\OcramiusProxyManager\Factory;
use Coduo\LazyObjects\Proxy\ClassName;
use Coduo\LazyObjects\Proxy\Definition;
use Coduo\LazyObjects\Proxy\Method;
use Coduo\LazyObjects\Proxy\Methods;
use Coduo\LazyObjects\Tests\Double\EntityFake;
use Coduo\LazyObjects\Wrapper;

$httpClient = new HttpClient('http://api.github.com');

$userProxyDefinition = new Definition(
    new ClassName("Github\User"),
    new Methods([
        new Method("getPublicRepositories", new Github\User\Proxy\Method\GetPublicRepositories($httpClient))
    ])
);

// Wrapper is ready, now we need to create user entity and wrap it
$wrapper = new Wrapper(new Factory(), [$userProxyDefinition]);

// lets assume that $httpApiClient will simply return us user data as an array.
$userData = $httpApiClient->getUserByLogin('norzechowicz');
$user = new User(new Login($userData['login']));

$userProxy = $wrapper->wrap($user);

$userProxy->getPublicRepositories(); // this will execute GetPublicRepositories::call method
```

As you can see thanks to lazy objects our domain classes are free from implementation details and unwanted methods
that could break our entire system when used without required knowledge.

