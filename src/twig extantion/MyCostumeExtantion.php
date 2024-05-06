<?php

namespace App\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class MyCustomTwigExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('defaultImage', [$this, 'defaultImage'])
        ];
    }

    public function defaultImage(?string $path): string
    {
        if (strlen(trim($path)==0)) {
            return 'content-mutsan-106.png';
        }
        return $path;
    }
}