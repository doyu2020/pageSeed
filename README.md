# Page Speed for hyperf 

# Install
> php 8.0

```
composer require dybee/pageseed
```

# Add dependencies

` config/autoload/dependencies.php 修改映射关系`

```phpregexp
\Hyperf\View\RenderInterface::class => \Dybee\PageSeed\Render\Render::class
```

# Add config

`修改config/autoload/view.php`

```
<?php
'engine' => HyperfViewEngine::class,
    'mode' => Mode::SYNC,
    'config' => [
        ....
        # pageSeed
        'pageSeed' => [
            # 开启或关闭
            'enable' => true,
            #开启选项
            'option' => [
                #移除 HTML 注释
                \Dybee\PageSeed\Middleware\RemoveComments::class,
                #移除 HTML 中不必要的空格
                \Dybee\PageSeed\Middleware\CollapseWhitespace::class
            ]
        ]
    ],
    ....
```
