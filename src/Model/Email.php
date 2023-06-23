<?php declare(strict_types=1);

namespace App\Model;


class Email
{
    public function __construct(private string $email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            throw new \InvalidArgumentException('Invalid value for Email');
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
