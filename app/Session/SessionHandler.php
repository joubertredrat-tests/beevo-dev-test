<?php
/**
 * SessionHandler -> From a given adapter type, apply it to PHP the core.
 *
 * This service needs to interact to required adapter independently,
 * validating it existance and it sanity, simulating the connection.
 *
 * @example https://www.php.net/manual/en/function.session-set-save-handler.php
 *
 * <code>
 *  // use file
 *  SessionHandler::register('file');
 *  $_SESSION['foo'] = 'bar'; // should write on file
 *  echo $_SESSION['foo']; // should return 'bar' from written file
 *
 *  // register weirdo adapter
 *  SessionHandler::register('THIS_IS_A_DUMMY_ADAPTER'); // Exception thrown
 * </code>
 */


namespace App\Session;

/**
 * Class SessionHandler
 */
class SessionHandler
{

    public $adapterType;


    /**
     * SessionService constructor.
     *
     * @param string $adapter Name of adapter (eg: redis)
     */
    final public function __construct($adapter)
    {
        $this->adapterType = $adapter;
    }


    public static function register($adapter)
    {
        return (new self($adapter))->getAdapter();
    }

    protected function getAdapter()
    {
        // ...
    }
}
