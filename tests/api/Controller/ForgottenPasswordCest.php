<?php namespace App\Tests\Controller;
use App\Tests\ApiTester;

class ForgottenPasswordCest
{
    public function _before(ApiTester $I)
    {
    }

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
        $I->sendGET('/user/forgotten_password', [
            'email' => 'user@example.com',
        ]);
        $I->seeEmailIsSent(1);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseContains('{"success":"Email has been successfully sent to you email address"}');
    }

    public function notRecogniseUserTest(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGET('/user/forgotten_password', [
            'email' => 'user@example.com',
        ]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $I->seeResponseContains('{"error":"The email address (user@example.com) has not been recognised."}');
    }

    public function onInvalidEmailAddressTest(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGET('/user/forgotten_password', [
            'email' => 'userexample.com',
        ]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $I->seeResponseContains('{"error":{"[email]":"This value is not a valid email address."}}');
    }

    public function onClosedAccountTest(ApiTester $I)
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


        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGET('/user/forgotten_password', [
            'email' => 'user@example.com',
        ]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $I->seeResponseContains('{"error":"That account is closed."}');
    }
}
