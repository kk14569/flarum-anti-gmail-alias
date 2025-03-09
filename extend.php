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
            $email = Arr::get($event->data, 'attributes.email');
            if (!empty($email) && $this->isGmailAlias($email)) {
                throw new ValidationException([
                    resolve('translator')->trans('kk14569-anti-gmail-alias.error.gmail_alias_message'),
                ]);
            }
        }),
];

function isGmailAlias(string $email): bool
{
    $pattern = '/^(?:(?:[a-zA-Z0-9]+(?:\.[a-zA-Z0-9]+)*\+[a-zA-Z0-9._%+-]+)|(?:[a-zA-Z0-9]+\.[a-zA-Z0-9]+))@(?:gmail|googlemail)\.com$/';
    return preg_match($pattern, $email) === 1;
}
