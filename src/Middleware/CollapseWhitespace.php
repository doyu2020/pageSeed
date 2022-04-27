<?php
declare(strict_types=1);

namespace Dybee\PageSeed\Middleware;

use Dybee\PageSeed\PageSeed;

class CollapseWhitespace extends PageSeed
{

    /**
     * Apply rules
     * @param string $buffer
     * @return string
     */
    public function apply(string $buffer): string
    {
        $replace = [
            "/\n([\S])/" => '$1',
            "/\r/" => '',
            "/\n/" => '',
            "/\t/" => '',
            "/ +/" => ' ',
            "/> +</" => '><',
        ];

        return $this->replace($replace, $this->removeComments($buffer));
    }

    protected function removeComments($buffer)
    {
        return make(RemoveComments::class)->apply($buffer);
    }
}