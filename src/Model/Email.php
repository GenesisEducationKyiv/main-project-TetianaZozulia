<?php declare(strict_types=1);

namespace App\Model;

use App\Exception\NotValidEmail;

class Email
{
    public function __construct(private string $email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            throw new NotValidEmail();
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
