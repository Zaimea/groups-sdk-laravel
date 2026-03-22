<?php

namespace Zaimea\SDK\Groups\Tests;

use Zaimea\SDK\Groups\Exceptions\ValidationException;
use Zaimea\SDK\Groups\Exceptions\NotFoundException;
use Zaimea\SDK\Groups\Exceptions\ForbiddenException;
use Zaimea\SDK\Groups\Exceptions\FailedActionException;
use Zaimea\SDK\Groups\Exceptions\RateLimitExceededException;
use Zaimea\SDK\Groups\Exceptions\TimeoutException;

class ExceptionsTest extends TestCase
{
    public function test_validation_exception_stores_errors()
    {
        $errors = [
            'name' => ['The name field is required.'],
            'email' => ['The email must be a valid email address.']
        ];

        $exception = new ValidationException($errors);

        $this->assertEquals('The given data failed to pass validation.', $exception->getMessage());
        $this->assertEquals($errors, $exception->errors());
    }

    public function test_not_found_exception_has_correct_message()
    {
        $exception = new NotFoundException();

        $this->assertEquals(
            'The resource you are looking for could not be found.',
            $exception->getMessage()
        );
    }

    public function test_forbidden_exception_can_have_empty_message()
    {
        $exception = new ForbiddenException();

        $this->assertEquals('', $exception->getMessage());
    }

    public function test_failed_action_exception_can_have_empty_message()
    {
        $exception = new FailedActionException();

        $this->assertEquals('', $exception->getMessage());
    }

    public function test_rate_limit_exception_stores_reset_time()
    {
        $resetTime = time() + 3600;
        $exception = new RateLimitExceededException($resetTime);

        $this->assertEquals('Too Many Requests.', $exception->getMessage());
        $this->assertEquals($resetTime, $exception->rateLimitResetsAt);
    }

    public function test_rate_limit_exception_accepts_null_reset_time()
    {
        $exception = new RateLimitExceededException(null);

        $this->assertEquals('Too Many Requests.', $exception->getMessage());
        $this->assertNull($exception->rateLimitResetsAt);
    }

    public function test_timeout_exception_stores_output()
    {
        $output = ['Command failed', 'Process timeout'];
        $exception = new TimeoutException($output);

        $this->assertEquals(
            'Script timed out while waiting for the process to complete.',
            $exception->getMessage()
        );
        $this->assertEquals($output, $exception->output());
    }

    public function test_all_exceptions_extend_base_exception()
    {
        $this->assertInstanceOf(
            \Exception::class,
            new ValidationException([])
        );
        $this->assertInstanceOf(
            \Exception::class,
            new NotFoundException()
        );
        $this->assertInstanceOf(
            \Exception::class,
            new ForbiddenException()
        );
        $this->assertInstanceOf(
            \Exception::class,
            new FailedActionException()
        );
        $this->assertInstanceOf(
            \Exception::class,
            new RateLimitExceededException(null)
        );
        $this->assertInstanceOf(
            \Exception::class,
            new TimeoutException([])
        );
    }
}