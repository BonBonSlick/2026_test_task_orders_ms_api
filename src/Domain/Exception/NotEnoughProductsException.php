<?php

declare(strict_types=1);

namespace App\Domain\Exception;

use Exception;

final class NotEnoughProductsException extends Exception
{

    public $message = 'Product quantity is not enough';

}
