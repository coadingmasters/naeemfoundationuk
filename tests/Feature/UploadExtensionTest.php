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

    public function test_an_mp4_video_is_still_accepted(): void
    {
        // Minimal MP4 header ("ftyp" box) so content sniffing sees a real video.
        $mp4 = "\x00\x00\x00\x18ftypmp42\x00\x00\x00\x00mp42isom".str_repeat("\x00", 64);

        $this->assertContains($this->ext($this->upload('clip.mp4', $mp4)), ['mp4', 'm4v']);
        // …but a PHP file renamed to .mp4 is not stored as PHP either.
        $this->assertNotContains($this->ext($this->upload('x.mp4', 'plain text')), ['php', 'phtml', 'phar']);
    }
}
