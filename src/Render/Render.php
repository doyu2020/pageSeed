<?php
declare(strict_types=1);

namespace Dybee\PageSeed\Render;

use Hyperf\Context\Context;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\View\Render as HyperfRender;
use Psr\Http\Message\ResponseInterface;

class Render extends HyperfRender
{
    /** @var string http mark key */
    const MARK_NAME = '__HTTP_TEMPLATE_MARK';

    public function render(string $template, array $data = []): ResponseInterface
    {
        $this->marks();
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

    /**
     * set a mark for httpTemplate
     * @return void
     */
    private function marks(): void
    {
        Context::set(self::MARK_NAME, true);
    }

    /**
     * validation for mark
     * @return bool
     */
    public function haskMark(): bool
    {
        return Context::get(self::MARK_NAME, false);
    }

}