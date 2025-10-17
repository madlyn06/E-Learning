<?php

namespace Newnet\Core\Support\Traits;

use Spatie\Translatable\HasTranslations as BaseHasTranslations;

trait TranslatableTrait
{
    use BaseHasTranslations;

    protected function asJson($value, $flags = 0)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    protected function getLocale(): string
    {
        return $this->translationLocale ?: request('edit_locale') ?? config('app.locale');
    }

    public function useFallbackLocale(): bool
    {
        return !is_admin_screen();
    }
}
