<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Session;

class AdminTest extends TestCase
{
    const SALT_PATH = "2hn439d2";
    const HASH_RUNS = 20000;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    public function testSuccessfulLoginPassword()
    {
        $hashed_test_password = self::hashPassword("TestAdminPassword");
        $testPassword = self::hashPassword("TestAdminPassword");
        $this->assertTrue($testPassword == $hashed_test_password);
    }

    public function testInvalidLoginPassword()
    {
        $hashed_test_password = self::hashPassword("TestAdminInvalid");
        $testPassword = self::hashPassword("TestAdminPassword");
        $this->assertFalse($testPassword == $hashed_test_password);
    }

    public static function hashPassword(string $plainTextPwd) : string
    {
        $hashedPwd = $plainTextPwd;
        $salt = self::SALT_PATH;

        for ($i = 0; $i < self::HASH_RUNS; $i++)
        {
            $hashedPwd = hash('sha256', $hashedPwd.$salt);
        } return $hashedPwd;
    }

}
