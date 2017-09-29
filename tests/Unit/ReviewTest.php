<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Validator;
use App\Review;

class ReviewTest extends TestCase
{

    /* Case #1 - Add a basic valid review */
    public function testValidatorBasicValid() {
        $validator = Validator::make([
            'restroom_id' => '1',
            'author' => 'Domenic Corso',
            'body' => 'This restroom was great!',
            'stars' => 5
        ], Review::getValidationRules());

        $this->assertTrue($validator->passes());
    }

    /* Case #2 - Reject review with a star rating of 0 */
    public function testValidatorWithNoStars() {
        $validator = Validator::make([
            'restroom_id' => '1',
            'author' => 'Domenic Corso',
            'body' => 'I did not like this restroom at all',
            'stars' => 0
        ], Review::getValidationRules());

        $this->assertFalse($validator->passes());
    }

    /* Case #3 - Reject review with star rating over 5 */
    public function testValidatorWithMoreThan5Stars() {
        $validator = Validator::make([
            'restroom_id' => '1',
            'author' => 'Domenic Corso',
            'body' => 'I did not like this restroom at all',
            'stars' => 6
        ], Review::getValidationRules());

        $this->assertFalse($validator->passes());
    }

    /* Case #4 - Reject review with star rating NOT an Integer */
    public function testValidatorStarsNotInt() {
        $validator = Validator::make([
            'restroom_id' => '1',
            'author' => 'Domenic Corso',
            'body' => 'I did not like this restroom at all',
            'stars' => 'rating'
        ], Review::getValidationRules());

        $this->assertFalse($validator->passes());
    }

    /* Case #5 - Add review without body */
    public function testValidatorWithNoBody() {
        $validator = Validator::make([
            'restroom_id' => '1',
            'author' => 'Domenic Corso',
            'body' => '',
            'stars' => 4
        ], Review::getValidationRules());

        $this->assertTrue($validator->passes());
    }

    /* Case #6 - Add review without author */
    public function testValidatorWithNoAuthor() {
        $validator = Validator::make([
            'restroom_id' => '1',
            'author' => '',
            'body' => 'I did not like this restroom at all',
            'stars' => 4
        ], Review::getValidationRules());

        $this->assertTrue($validator->passes());
    }

    /* Case #7 - Add review with invalid author text */
    public function testValidatorWithInvalidAuthor() {
        $validator = Validator::make([
            'restroom_id' => '1',
            'author' => '&&&@@@@www',
            'body' => 'I did not like this restroom at all',
            'stars' => 4
        ], Review::getValidationRules());

        $this->assertFalse($validator->passes());
    }

    /* Case #8 - Add review with invalid body text */
    public function testValidatorWithInvalidBody() {
        $validator = Validator::make([
            'restroom_id' => '1',
            'author' => 'Domenic Corso',
            'body' => 'I completely%%& did not Like ) him',
            'stars' => 4
        ], Review::getValidationRules());

        $this->assertFalse($validator->passes());
    }

}
