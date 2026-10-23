<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class LithuanianTranslationsTest extends TestCase
{
    private const string PROJECT_ROOT = __DIR__.'/../..';

    /**
     * @return array<string, string>
     */
    private function lithuanianTranslations(): array
    {
        return json_decode(file_get_contents(self::PROJECT_ROOT.'/lang/lt.json'), true, flags: JSON_THROW_ON_ERROR);
    }

    /**
     * Every string passed to __() or $fail(...)->translate() in app/.
     *
     * @return list<string>
     */
    private function translationKeysUsedInApp(): array
    {
        $patterns = [
            "/__\(\s*'((?:[^'\\\\]|\\\\.)*)'/",
            "/\\\$fail\(\s*'((?:[^'\\\\]|\\\\.)*)'\)\s*->translate/",
        ];

        $keys = [];

        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(self::PROJECT_ROOT.'/app'));

        foreach ($files as $file) {
            if (! $file->isFile() || $file->getExtension() !== 'php') {
                continue;
            }

            $contents = file_get_contents($file->getPathname());

            foreach ($patterns as $pattern) {
                preg_match_all($pattern, $contents, $matches);

                array_push($keys, ...$matches[1]);
            }
        }

        return array_values(array_unique(array_map(stripslashes(...), $keys)));
    }

    public function test_every_translation_key_used_in_app_has_lithuanian_translation(): void
    {
        $keysUsedInApp = $this->translationKeysUsedInApp();

        $missingKeys = array_values(array_diff($keysUsedInApp, array_keys($this->lithuanianTranslations())));

        $this->assertNotEmpty($keysUsedInApp);
        $this->assertSame([], $missingKeys, 'Missing in lang/lt.json: '.implode(', ', $missingKeys));
    }

    public function test_lithuanian_translations_are_not_empty_or_untranslated(): void
    {
        foreach ($this->lithuanianTranslations() as $key => $translation) {
            $this->assertNotSame('', trim($translation), "Empty translation for \"{$key}\"");
        }
    }

    public function test_validation_translations_keep_their_placeholders(): void
    {
        foreach ($this->lithuanianTranslations() as $key => $translation) {
            preg_match_all('/:\w+/', $key, $placeholders);

            foreach ($placeholders[0] as $placeholder) {
                $this->assertStringContainsString($placeholder, $translation, "\"{$key}\" lost {$placeholder}");
            }
        }
    }
}
