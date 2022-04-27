<?php

namespace RenatoMarinho\LaravelPageSpeed\Entities;

namespace Dybee\PageSeed\Entities;

class HtmlSpecs
{
    public static function voidElements(): array
    {
        return [
            'area',
            'base',
            'br',
            'col',
            'embed',
            'hr',
            'img',
            'input',
            'link',
            'meta',
            'param',
            'source',
            'track',
            'wbr',
        ];
    }
}
