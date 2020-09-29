<?php namespace App\Tests\Controller\User;
use App\Entity\User;
use App\Tests\ApiTester;

class RegisterUserCest
{
    public function registerUserOnSuccessTest(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/register/user', [
            'email'                => 'user@example.com',
            'password'             => 'Password1',
            'passwordConfirmation' => 'Password1',
        ]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::CREATED); // 201
        $I->seeResponseIsJson();
        $I->seeResponseContains('{"success":"User registered!"}');
        $I->grabEntityFromRepository(User::class, ['email' => 'user@example.com']);
    }

    public function registerUserOnPasswordsDontMatchTest(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/register/user', [
            'email'                => 'user@example.com',
            'password'             => 'Password1',
            'passwordConfirmation' => 'password',
        ]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $I->seeResponseContains('{"password":["Password does not match the password confirmation."]}');
        $I->dontSeeInRepository(User::class, ['email' => 'user@example.com']);
    }

    public function registerUserOnPasswordNeedsUppercaseLetterTest(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/register/user', [
            'email'                => 'user@example.com',
            'password'             => 'password1',
            'passwordConfirmation' => 'password1',
        ]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $I->seeResponseContains('{"password":["Your password needs to contain a uppercase"]}');
        $I->dontSeeInRepository(User::class, ['email' => 'user@example.com']);
    }

    public function registerUserOnPasswordNeedsNumberTest(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/register/user', [
            'email'                => 'user@example.com',
            'password'             => 'ChangeMe',
            'passwordConfirmation' => 'ChangeMe',
        ]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $I->seeResponseContains('{"password":["Your password needs to contains at least one number"]}');
        $I->dontSeeInRepository(User::class, ['email' => 'user@example.com']);
    }
}
