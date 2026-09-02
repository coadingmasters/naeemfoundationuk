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

    /**
     * Like storeUploadedImage(), but downscales the photo first so an admin
     * can upload a full-size phone/camera photo straight from their device —
     * no manual resizing needed. Only shrinks (never upscales); re-encodes at
     * a web-friendly quality. Falls back to a plain move if GD can't decode
     * the file (e.g. an unusual format), so an upload never hard-fails.
     */
    protected function storeResizedImage(UploadedFile $file, string $dir, string $prefix, int $maxWidth = 1920): string
    {
        $ext = strtolower($file->getClientOriginalExtension()) ?: 'jpg';
        if (! in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
            $ext = 'jpg';
        }

        $name = $prefix.'-'.now()->format('YmdHis').'-'.substr(md5(uniqid('', true)), 0, 8).'.'.$ext;
        $destDir = $this->webRoot().'/'.$dir;
        if (! is_dir($destDir)) {
            @mkdir($destDir, 0755, true);
        }
        $destPath = $destDir.'/'.$name;

        $source = match ($ext) {
            'png' => @imagecreatefrompng($file->getRealPath()),
            'webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($file->getRealPath()) : false,
            default => @imagecreatefromjpeg($file->getRealPath()),
        };

        if (! $source) {
            // GD couldn't decode it — store the original as-is rather than fail the upload.
            $file->move($destDir, $name);

            return $dir.'/'.$name;
        }

        $width = imagesx($source);
        $height = imagesy($source);

        if ($width > $maxWidth) {
            $newWidth = $maxWidth;
            $newHeight = (int) round($height * ($maxWidth / $width));
            $resized = imagecreatetruecolor($newWidth, $newHeight);

            if ($ext === 'png') {
                imagealphablending($resized, false);
                imagesavealpha($resized, true);
            }

            imagecopyresampled($resized, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($source);
            $source = $resized;
        }

        match ($ext) {
            'png' => imagepng($source, $destPath, 6),
            'webp' => function_exists('imagewebp') ? imagewebp($source, $destPath, 82) : imagejpeg($source, $destPath, 82),
            default => imagejpeg($source, $destPath, 82),
        };
        imagedestroy($source);

        return $dir.'/'.$name;
    }
}
