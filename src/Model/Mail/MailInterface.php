<?php declare(strict_types=1);

namespace App\Model\Mail;

use App\Model\Email;

interface MailInterface
{
    /**
     * @param Email $to
     */
    public function setTo(Email $to): void;

    /**
     * @param string $txt
     */
    public function setTxt(string $txt): void;

    /**
     * @return ?Email
     */
    public function getFrom(): ?Email;

    /**
     * @return Email
     */
    public function getTo(): Email;

    /**
     * @return ?string
     */
    public function getSubject(): ?string;

    /**
     * @return string
     */
    public function getTxt(): string;

    /**
     * @return string
     */
    public function getHtml(): string;
}
