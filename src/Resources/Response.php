<?php

declare(strict_types=1);

namespace Zaimea\SDK\Groups\Resources;

class Response extends Resource
{
    /**
     * The status of response.
     *
     * @var string
     */
    public $status;

    /**
     * Message of response.
     *
     * @var string
     */
    public $message;
}