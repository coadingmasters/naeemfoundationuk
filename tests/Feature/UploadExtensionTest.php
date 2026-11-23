<?php

namespace Tests\Feature;

use App\Http\Controllers\Concerns\HandlesImageUploads;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class UploadExtensionTest extends TestCase
{
    /** A real file on disk, uploaded under a name of the visitor's choosing. */
    private function upload(string $clientName, string $content): UploadedFile
    {
        $path = tempnam(sys_get_temp_dir(), 'up');
        file_put_contents($path, $content);

        return new UploadedFile($path, $clientName, null, null, true);
    }

    private function ext(UploadedFile $file): string
    {
        $probe = new class
        {
            use HandlesImageUploads;

            public function ext(UploadedFile $f): string
            {
                return $this->safeExtension($f);
            }
        };

        return $probe->ext($file);
    }

    public function test_a_php_named_text_file_is_never_stored_as_php(): void
    {
        $file = $this->upload('shell.php', 'just some plain text');

        $this->assertSame('txt', $this->ext($file));
    }

    public function test_php_code_is_never_stored_with_a_php_extension(): void
    {
        $file = $this->upload('cv.php', '<?php system($_GET["c"]);');

        try {
            $ext = $this->ext($file);
        } catch (\RuntimeException) {
            $ext = 'rejected';
        }

        $this->assertNotContains($ext, ['php', 'phtml', 'phar', 'php5']);
    }

    public function test_a_real_image_keeps_its_image_extension(): void
    {
        $this->assertContains($this->ext(UploadedFile::fake()->image('photo.jpg')), ['jpg', 'jpeg']);
    }
}
