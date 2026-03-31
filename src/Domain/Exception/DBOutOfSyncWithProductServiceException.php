<?php

declare(strict_types=1);

namespace App\Domain\Exception;

use Exception;

final class DBOutOfSyncWithProductServiceException extends Exception
{

    public $message = 'Database is out of sync with products microservice';

}
