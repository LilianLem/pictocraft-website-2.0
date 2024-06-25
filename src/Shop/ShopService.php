<?php

namespace App\Shop;

class ShopService
{
    /**
     * @param string[] $slugs
     * @return array<string, string>
     */
    protected function addKeysToSlugArray(array $slugs): array {
        $i = 1;
        $slugsWithKeys = [];
        foreach($slugs as $slug) {
            $slugsWithKeys["slug$i"] = $slug;
            $i++;
        }

        return $slugsWithKeys;
    }
}