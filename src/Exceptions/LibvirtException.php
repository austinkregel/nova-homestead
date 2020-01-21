<?php

namespace Kregel\Homestead\Exceptions;

use Throwable;

class LibvirtException extends \DomainException
{
    public function __construct($message = "", Throwable $previous = null)
    {
        parent::__construct($message, 400, $previous);
    }

    public static function throw(string $message, ?Throwable $previous = null)
    {
        throw new self($message, $previous);
    }
}
