<?php
declare(strict_types=1);

namespace Dybee\PageSeed;


use Dybee\PageSeed\Entities\HtmlSpecs;
use Hyperf\HttpServer\CoreMiddleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;


abstract class PageSeed
{

    /**
     * Apply rules
     * @param string $buffer
     * @return string
     */
    abstract public function apply(string $buffer): string;
//
//    protected function transferToResponse($response, ServerRequestInterface $request): ResponseInterface
//    {
//        if (!is_string($response)) parent::transferToResponse($response, $request);
//        $response = $this->apply($response);
//        return $this->transferToResponse($response, $request);
//    }

    /**
     * Match all occurrences of the html tags given
     *
     * @param array $tags Html tags to match in the given buffer
     * @param string $buffer Middleware response buffer
     *
     * @return array $matches Html tags found in the buffer
     */
    protected function matchAllHtmlTag(array $tags, string $buffer): array
    {
        $voidTags = array_intersect($tags, HtmlSpecs::voidElements());
        $normalTags = array_diff($tags, $voidTags);

        return array_merge(
            $this->matchTags($voidTags, '/\<\s*(%tags)[^>]*\>/', $buffer),
            $this->matchTags($normalTags, '/\<\s*(%tags)[^>]*\>((.|\n)*?)\<\s*\/\s*(%tags)\>/', $buffer)
        );
    }

    protected function matchTags(array $tags, string $pattern, string $buffer): array
    {
        if (empty($tags)) {
            return [];
        }

        $normalizedPattern = str_replace('%tags', implode('|', $tags), $pattern);

        preg_match_all($normalizedPattern, $buffer, $matches);

        return $matches[0];
    }

    /**
     * Replace occurrences of regex pattern inside of given HTML tags
     *
     * @param array $tags Html tags to match and run regex to replace occurrences
     * @param string $regex Regex rule to match on the given HTML tags
     * @param string $replace Content to replace
     * @param string $buffer Middleware response buffer
     *
     * @return string $buffer Middleware response buffer
     */
    protected function replaceInsideHtmlTags(array $tags, string $regex, string $replace, string $buffer): string
    {
        foreach ($this->matchAllHtmlTag($tags, $buffer) as $tagMatched) {
            preg_match_all($regex, $tagMatched, $contentsMatched);

            $tagAfterReplace = str_replace($contentsMatched[0], $replace, $tagMatched);
            $buffer = str_replace($tagMatched, $tagAfterReplace, $buffer);
        }

        return $buffer;
    }

    /**
     * Replace content response.
     *
     * @param array $replace
     * @param string $buffer
     * @return string
     */
    protected function replace(array $replace, $buffer)
    {
        return preg_replace(array_keys($replace), array_values($replace), $buffer);
    }
}