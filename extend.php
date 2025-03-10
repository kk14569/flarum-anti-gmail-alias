<?php

namespace kk14569\FlarumAntiGmailAlias;

use Flarum\Extend;
use Flarum\Foundation\ValidationException;
use Flarum\User\Event\Saving;
use Illuminate\Support\Arr;

return [
    (new Extend\Locales(__DIR__.'/locale')),
    (new Extend\Event())
        ->listen(Saving::class, function (Saving $event) {
            $email = strtolower(Arr::get($event->data, 'attributes.email'));
            if (!empty($email) && isGmailAlias($email)) {
                throw new ValidationException([
                    'email' => resolve('translator')->trans('kk14569-anti-gmail-alias.error.gmail_alias_message'),
                ]);
            }
        }),
];

function isGmailAlias(string $email): bool
{
    $parts = explode('@', $email);
    if (count($parts) !== 2) return false;
    [$localPart, $domain] = $parts;
    if (!in_array($domain, ['gmail.com', 'googlemail.com'])) {
        return false;
    }
    return str_contains($localPart, '+') || str_contains($localPart, '.');
}
