<?php
declare(strict_types=1);

namespace Dybee\PageSeed\Middleware;

use Dybee\PageSeed\PageSeed;

/**
 * 移除 HTML 注释
 */
class RemoveComments extends PageSeed
{
    const REGEX_MATCH_JS_AND_CSS_COMMENTS = '/(?:(?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:(?<!\:|\\\|\'|\")\/\/.*))/';
    const REGEX_MATCH_HTML_COMMENTS = '/<!--[^]><!\[](.*?)[^\]]-->/s';

    public function apply(string $buffer): string
    {
        $buffer = $this->replaceInsideHtmlTags(['script', 'style'], self::REGEX_MATCH_JS_AND_CSS_COMMENTS, '', $buffer);

        $replaceHtmlRules = [
            self::REGEX_MATCH_HTML_COMMENTS => '',
        ];

        return $this->replace($replaceHtmlRules, $buffer);
    }
}