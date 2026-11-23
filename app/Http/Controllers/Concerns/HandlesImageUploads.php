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
        $name = $prefix.'-'.now()->format('YmdHis').'-'.substr(md5(uniqid('', true)), 0, 8).'.'.$this->safeExtension($file);
        $file->move($this->webRoot().'/'.$dir, $name);

        return $dir.'/'.$name;
    }

    /**
     * The extension to store an upload under. Decided from the file's real
     * content (never trusted from the visitor's file name), and only ever from
     * a short allow-list — so an upload can never be saved as .php/.phtml and
     * run by the web server.
     */
    protected function safeExtension(UploadedFile $file): string
    {
        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'pdf', 'doc', 'docx', 'odt', 'rtf', 'txt', 'zip',
            // the admin video uploads
            'mp4', 'm4v', 'mov', 'webm', 'ogg', 'ogv', 'mpeg', 'mpg', 'avi'];

        $guessed = strtolower((string) $file->guessExtension());
        if (in_array($guessed, $allowed, true)) {
            return $guessed;
        }

        // Content sniffing can name a valid .docx as "zip"; accept the visitor's
        // own extension only if it is also on the allow-list.
        $client = strtolower($file->getClientOriginalExtension());
        if (in_array($client, $allowed, true)) {
            return $client;
        }

        throw new \RuntimeException('This file type is not allowed.');
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
