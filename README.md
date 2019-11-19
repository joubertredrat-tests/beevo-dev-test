# Interview Session Adapter
#### From a given adapter type, apply it to PHP the core. 

This is an interview code excercise to deal with session adapters on PHP.

___

The main goal here is to create multiple adapters to apply on session handler, interacting to required adapter independently after validating it existance and it sanity.
**The real usage it's not required here** (eg: persist session data on DB/Redis, etc.) - the main goal is to write a real functional code.

### Usage goal:
```php
SessionHandler::register('files');
$_SESSION['foo'] = 'bar'; // should write on file
echo $_SESSION['foo']; // should return 'bar' from written file

// register weirdo adapter
SessionHandler::register('THIS_IS_A_DUMMY_ADAPTER'); // Exception thrown
```

### Useful links:
- [PHP: session_set_save_handler - Manual](https://www.php.net/manual/en/function.session-set-save-handler.php)
- [Refactoring Guru - Adapter Pattern](https://refactoring.guru/design-patterns/adapter)
- [Refactoring Guru - Factory Pattern](https://refactoring.guru/design-patterns/factory-method)

___

**©Beevo - 2019**
