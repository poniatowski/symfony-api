<?php namespace App\Tests\Controller;
use App\Tests\ApiTester;

class CloseAccountCest
{
    public function closeAccountOnSuccessTest(ApiTester $I)
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

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPATCH('/user/close_account');
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseContains('{"success":"You\u0027ve been logged out."}');
    }

    public function closeAccountOnNotSessionExistTest(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPATCH('/user/close_account');
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::UNAUTHORIZED);
        $I->seeResponseIsJson();
        $I->seeResponseContains('{"message":"Authentication Required"}');
    }
}
