<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Format content (linkify URLs and highlight hashtags) before saving
        if (isset($data['content']) && ! empty($data['content'])) {
            $data['content'] = $this->formatContent($data['content']);
        }

        return $data;
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
