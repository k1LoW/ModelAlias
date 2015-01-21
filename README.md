# ModelAlias

Use alias in `$this->uses`

```php
<?php
App::uses('Controller', 'Controller');

/**
 * Application level Controller
 *
 */
class AppController extends Controller
{
    use ModelAliasTrait;

    public function __construct($request = null, $response = null)
    {
        // ModelAliasTrait
        $this->initUsesAlias();
        parent::__construct($request, $response);
    }

    public function loadModel($modelClass = null, $id = null)
    {
        // ModelAliasTrait
        return $this->loadAliasModel($modelClass, $id);
    }
}
```

```php
<?php

class AdminUsersController extends AppController
{
    public $uses = [
        'User' => ['className' => 'AdminUser'],
    ];

    public function view($id = null)
    {
        $this->set(['user' => $this->User->view($id)]);
    }
}
```
