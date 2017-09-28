<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\RestroomPhoto;
use App\Restroom;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class RestroomPhotoTest extends TestCase
{
    const VALID_FILETYPES = array('png', 'jpeg', 'jpg');

    /*
     * Testing for valid file extension of uploaded image
     * Valid file types are specified above
     */

    public function testValidFileExtension()
    {
        $validImage = public_path("img/rp/baby.png");

        $validImageExt = pathinfo($validImage, PATHINFO_EXTENSION);

        $this->assertTrue(in_array($validImageExt, self::VALID_FILETYPES));
    }

    /*
     * Testing for invalid file extension of uploaded image
     * Valid file types are specified above
     */

    public function testInvalidFileExtension()
    {
        $invalidImage = public_path("img/rp/baby.js");

        $invalidImageExt = pathinfo($invalidImage, PATHINFO_EXTENSION);

        $this->assertTrue(!in_array($invalidImageExt, self::VALID_FILETYPES));
    }

    /*
     * Testing uploading by uploading a fake image
     * Assert true if the photo has been uploaded successfully with correct size and file extension
     */

    public function testValidPhotoUpload()
    {
        /* Create a post request to the add restroom passing through a fake image */

        $response = $this->call('POST', '/add-restroom', array(
                '_token' => csrf_token(),
                'rr_name' => 'RMIT Building 80',
                'rr_desc' => 'Cleaner than other toilets in RMIT',
                'rr_lat' => '37.002',
                'rr_lng' => '-144.020492',
                'rr_floor' => '10',
                'rr_added_by' => 'Lu',
                'rr_photos' => UploadedFile::fake()->image('testimage' ,600, 600)->move(public_path("/img/rp"))
        ));

        /* Get all the images in the img/rp folder */
        $path = public_path('img/rp/');
        $files = glob($path.'*');

        /* If the files arent 0 and the size is not 0, then assert true and the path ext is in the array */
        $this->assertTrue(count($files) != 0 && self::validFileSizeType($files));

        foreach ($files as $f) {
            unlink($f);
        }
    }

    /*
     * Testing uploading by uploading a fake image
     * Assert false if the photo size and file extension doesnt match
     */

    public function testInvalidPhotoUpload()
    {
        /* Create a post request to the add restroom passing through a fake image */

        $response = $this->call('POST', '/add-restroom', array(
                '_token' => csrf_token(),
                'rr_name' => 'RMIT Building 80',
                'rr_desc' => 'Cleaner than other toilets in RMIT',
                'rr_lat' => '37.002',
                'rr_lng' => '-144.020492',
                'rr_floor' => '10',
                'rr_added_by' => 'Lu',
                'rr_photos' => UploadedFile::fake()->create('testdoc.pdf' , 2500)->move(public_path("/img/rp"))
        ));

        /* Get all the images in the img/rp folder */
        $path = public_path('img/rp/');
        $files = glob($path.'*');

        /* If the files arent 0 and the size is 0, then assert false and the path ext isnt in the array */
        $this->assertFalse(count($files) != 0 && self::validFileSizeType($files));

        foreach ($files as $f) {
            unlink($f);
        }
    }

    private static function validFileSizeType(array $files) : bool
    {
        foreach($files as $f) {
            $fext = pathinfo($f, PATHINFO_EXTENSION);
            if (filesize($f) == 0 && !in_array($fext, self::VALID_FILETYPES)) { return false; } else { return true; }
        }
    }
}
