<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Convert HTML-formatted content back to plain text for editing
        if (isset($data['content']) && ! empty($data['content'])) {
            $data['content'] = $this->toRawTextFromFormatted($data['content']);
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Format content (linkify URLs and highlight hashtags) before saving
        if (isset($data['content']) && ! empty($data['content'])) {
            $data['content'] = $this->formatContent($data['content']);
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    /**
     * Convert formatted HTML content back to raw text.
     */
    private function toRawTextFromFormatted(string $html): string
    {
        if ($html === '') {
            return '';
        }

        // Replace anchor tags with their href (fallback to inner text if no href)
        $intermediate = preg_replace_callback('/<a[^>]*href="([^"]*)"[^>]*>(.*?)<\/a>/is', function ($m) {
            $href = trim($m[1] ?? '');
            $text = trim(strip_tags($m[2] ?? ''));

            return $href !== '' ? $href : $text;
        }, $html);

        // Replace <br> with newlines
        $intermediate = preg_replace('/<br\s*\/?>(\r?\n)?/i', "\n", $intermediate);

        // Strip remaining tags and decode entities
        $stripped = strip_tags($intermediate);
        $decoded = html_entity_decode($stripped, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // Normalize whitespace
        $decoded = preg_replace("/\r?\n\s*\r?\n+/", "\n\n", $decoded);

        return $decoded;
    }

    /**
     * Format content by linkifying URLs and highlighting hashtags.
     */
    private function formatContent(string $raw): string
    {
        $withLinks = $this->linkifyUrls($raw);

        return $this->highlightHashtags($withLinks);
    }

    private function linkifyUrls(string $text): string
    {
        $pattern = '/(?<![\"\'>])(https?:\/\/[^\s<]+)/i';

        return preg_replace_callback($pattern, function ($matches) {
            $url = $matches[1];
            $display = strlen($url) > 80 ? substr($url, 0, 77).'…' : $url;
            $safeUrl = e($url);
            $safeDisplay = e($display);

            return '<a href="'.$safeUrl.'" target="_blank" rel="nofollow noopener" class="text-indigo-600 hover:underline">'.$safeDisplay.'</a>';
        }, e($text));
    }

    private function highlightHashtags(string $html): string
    {
        // Replace hashtags only in text nodes (outside of HTML tags)
        return preg_replace_callback('/(^|>)([^<]+)(?=<|$)/', function ($m) {
            $prefix = $m[1];
            $text = $m[2];
            $replaced = preg_replace('/(^|\s)#(\w+)/', '$1<span class="text-indigo-600 font-semibold">#$2</span>', $text);

            return $prefix.$replaced;
        }, $html);
    }
}
