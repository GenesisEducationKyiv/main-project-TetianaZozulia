<?php declare(strict_types=1);

namespace App\Model;

class Email
{
    public function __construct(private string $email)
    {
        //we can add validation here
        if (! $email) {
            throw new \InvalidArgumentException('Invalid argument for Email');
        }
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }
}
