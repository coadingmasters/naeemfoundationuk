<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Http\UploadedFile;

trait HandlesImageUploads
{
    /**
     * The directory the web server actually serves from.
     *
     * On Hostinger (and similar shared hosting) the public document root is
     * `public_html`, a sibling of the Laravel app folder — NOT the app's own
     * `public/`. Writing uploads to public_path() there saves them somewhere
     * the browser can't reach. We detect that layout and use it; otherwise we
     * fall back to the standard public path (local dev, normal hosting).
     */
    protected function webRoot(): string
    {
        $docroot = base_path('../public_html');

        return is_dir($docroot) ? $docroot : public_path();
    }

    /**
     * Move an uploaded image into a sub-directory of the web root and return
     * its path relative to that root (e.g. "images/appeals/appeal-...jpg").
     */
    protected function storeUploadedImage(UploadedFile $file, string $dir, string $prefix): string
    {
        $name = $prefix.'-'.now()->format('YmdHis').'-'.substr(md5(uniqid('', true)), 0, 8).'.'.$file->getClientOriginalExtension();
        $file->move($this->webRoot().'/'.$dir, $name);

        return $dir.'/'.$name;
    }

    /** Remove a previously uploaded image (only ever within its own directory). */
    protected function deleteUploadedImage(?string $path, string $dir): void
    {
        if ($path && str_starts_with($path, $dir.'/')) {
            $full = $this->webRoot().'/'.$path;
            if (is_file($full)) {
                @unlink($full);
            }
        }
    }
}
