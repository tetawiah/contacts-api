<?php

namespace Tests\Feature;

use App\Events\FileUploaded;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileUploadTest extends TestCase
{
    /**
     * A basic feature test example.
     */
//wont work without authenticated user
    public function test_to_upload_file()
    {
        $this->withoutExceptionHandling();

        $file = UploadedFile::fake()->create('test.csv', 100);
        $this->postJson("/api/contact-upload", ['csv' => $file]);
        $this->assertTrue(Storage::exists('uploads/' . $file->getClientOriginalName()));
    }

    public function test_mail_sent()
    {
        $this->withoutExceptionHandling();

        $this->actingAs(User::factory()->create(
            ["email" => "dedasih840@onlcool.com"]
        ));
        $file = UploadedFile::fake()->create('test.csv');

        event(new FileUploaded($file));
        Mail::assertSent(function ($mail) {
            return $mail->hasTo(auth()->user()->email) && $mail->subject === "New File Uploaded";
        });

    }
}
