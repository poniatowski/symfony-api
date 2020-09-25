<?php namespace App\Tests\Controller;
use App\Tests\ApiTester;

class LoginCest
{
    public function loginOnSuccessTest(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/register/user', [
            'email'                => 'user@example.com',
            'password'             => 'Password1',
            'passwordConfirmation' => 'Password1',
        ]);

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/login', [
            'email'                => 'user@example.com',
            'password'             => 'Password1',
        ]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::NO_CONTENT);
    }

    public function loginOnInvalidUserTest(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/login', [
            'email'                => 'user@example.com',
            'password'             => 'Password1',
        ]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::UNAUTHORIZED);
        $I->seeResponseIsJson();
        $I->seeResponseContains('{"message":"Username could not be found."}');
    }
}
