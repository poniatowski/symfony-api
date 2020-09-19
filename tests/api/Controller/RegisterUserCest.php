<?php namespace App\Tests\Controller;
use App\Tests\ApiTester;

class RegisterUserCest
{
    public function _before(ApiTester $I)
    {
    }

    // tests
    public function tryToTest(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/register/user', [
            'email'                => 'user@example.com',
            'password'             => 'Password1',
            'passwordConfirmation' => 'Password1',
        ]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::CREATED); // 201
        $I->seeResponseIsJson();
        $I->seeResponseContains('{"status":"User registered!"}');
    }
}
