<?php 

namespace App\Attribute;

#[\Attribute(\Attribute::TARGET_CLASS)]
class AutoSlug
{
    public function __construct(
        public string $source,
        public string $target = 'slug'
    ) {}
}