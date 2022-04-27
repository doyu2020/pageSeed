<?php
declare(strict_types=1);

namespace Dybee\PageSeed\Render;

use Dybee\PageSeed\Middleware\RemoveComments;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\View\Render as HyperfRender;
use Psr\Http\Message\ResponseInterface;

class Render extends HyperfRender
{
    public function render(string $template, array $data = []): ResponseInterface
    {
        $html = $this->getContents($template, $data);
        if (isset($this->config['pageSeed']['enable']) && $this->config['pageSeed']['enable']) {
            foreach ($this->config['pageSeed']['option'] as $option) {
                $html = make($option)->apply($html);
            }
        }
        return $this->response()
            ->withAddedHeader('content-type', $this->getContentType())
            ->withBody(new SwooleStream($html));
    }

}