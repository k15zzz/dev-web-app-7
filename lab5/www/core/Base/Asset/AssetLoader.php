<?php

namespace Core\Base\Asset;

use Exception;

class AssetLoader
{
    private $manifest;

    public function __construct($manifestPath)
    {
        if (!file_exists($manifestPath)) {
            throw new Exception('Manifest file does not exist.');
        }
        $this->manifest = json_decode(file_get_contents($manifestPath), true);
    }

    public function getAssetPath($key)
    {
        if (!isset($this->manifest[$key])) {
            return '';
        }
        return $this->manifest[$key];
    }

    public function includeScript($key)
    {
        $path = $this->getAssetPath($key);
        echo "<script type='module' src='{$path}'></script>";
    }

    public function includeStyle($key)
    {
        $path = $this->getAssetPath($key . '.css');
        if (!$path) return;
        echo "<link rel='stylesheet' href='$path'/>";
    }
}