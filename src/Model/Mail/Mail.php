<?php declare(strict_types=1);

namespace App\Model\Mail;

use App\Model\Email;

class Mail
{
    // add more arguments for cc, attach files...
    public function __construct(
        private Email $to,
        private string $txt,
        private string $html,
        private ?Email $from,
        private ?string $subject,
    )
    {
    }

    /**
     * @param Email $to
     */
    public function setTo(Email $to): void
    {
        $this->to = $to;
    }

    /**
     * @return ?Email
     */
    public function getFrom(): ?Email
    {
        return $this->from;
    }

    /**
     * @return Email
     */
    public function getTo(): Email
    {
        return $this->to;
    }

    /**
     * @return ?string
     */
    public function getSubject(): ?string
    {
        return $this->subject;
    }

    /**
     * @return string
     */
    public function getTxt(): string
    {
        return $this->txt;
    }

    /**
     * @return string
     */
    public function getHtml(): string
    {
        return $this->html;
    }
}
