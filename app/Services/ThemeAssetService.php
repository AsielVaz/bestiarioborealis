<?php

namespace App\Services;

use App\Models\DossierTheme;
use App\Models\DossierThemeAsset;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ThemeAssetService
{
    public function uploadThemeAsset(DossierTheme $theme, string $type, UploadedFile $file): DossierThemeAsset
    {
        $path = $file->store("themes/{$theme->key}", 'public');
        [$width, $height] = @getimagesize($file->getRealPath()) ?: [null, null];

        return $theme->assets()->create(compact('type', 'path', 'width', 'height'));
    }

    public function replaceThemeAsset(DossierThemeAsset $asset, UploadedFile $file): DossierThemeAsset
    {
        Storage::disk('public')->delete($asset->path);
        $path = $file->store("themes/{$asset->dossierTheme->key}", 'public');
        [$width, $height] = @getimagesize($file->getRealPath()) ?: [null, null];

        $asset->update(compact('path', 'width', 'height'));

        return $asset;
    }
}
