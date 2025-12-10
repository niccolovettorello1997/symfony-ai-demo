<?php

declare(strict_types=1);

namespace App\Utils;

class HTMLToTextConverter
{
    /**
     * Converts html into plain text.
     *
     * @param  string $html
     * @return string
     */
    public function toPlainText(string $html): string
    {
        // Remove HTML tags to get plain text
        $plainText = strip_tags($html);

        // Normalize whitespace
        $plainText = preg_replace('/\s+/', ' ', $plainText);

        // Trim leading and trailing whitespace
        return trim($plainText);
    }
}
