<?php namespace App\Tests\Controller;
use App\Tests\ApiTester;
use App\Entity\User;
use DateTime;

class ResetPasswordCest
{
    public function resetPasswordOnSuccessTest(ApiTester $I)
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

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGET('/user/forgotten_password', [
            'email' => 'user@example.com',
        ]);


        $user = $I->grabEntityFromRepository(User::class, array('email' => 'user@example.com'));

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/user/reset_password/' . $user->getForgottenPasswordToken(), [
            'newPassword'             => 'Password2',
            'newPasswordConfirmation' => 'Password2'
        ]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseContains('{"success":"Your password has been successfully updated."}');

        $user = $I->grabEntityFromRepository(User::class, [
            'email' => 'user@example.com'
        ]);
        $I->assertSame('user@example.com', $user->getEmail());
        $I->assertNull($user->getForgottenPasswordToken());
        $I->assertNull($user->getSentForgottenPassword());
    }

    public function onAlreadyTakenTokenTest(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/user/reset_password/TOKEN', [
            'newPassword'             => 'Password2',
            'newPasswordConfirmation' => 'Password2'
        ]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $I->seeResponseContains('{"error":"The token has been already used."}');
    }

    public function onExpiredTokenTest(ApiTester $I)
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

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGET('/user/forgotten_password', [
            'email' => 'user@example.com',
        ]);


        $sentForgottenPasswordEmail = new DateTime();
        $sentForgottenPasswordEmail->modify('-1 month');

        $user = $I->grabEntityFromRepository(User::class, array('email' => 'user@example.com'));
        $user->setSentForgottenPassword($sentForgottenPasswordEmail);

        $I->seeInRepository(User::class, ['sentForgottenPassword' => $sentForgottenPasswordEmail]);

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/user/reset_password/' . $user->getForgottenPasswordToken(), [
            'newPassword'             => 'Password2',
            'newPasswordConfirmation' => 'Password2'
        ]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $I->seeResponseContains('{"error":"The password token has expired."}');
    }

    public function onNonmatchConfirmationPasswordTest(ApiTester $I)
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

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGET('/user/forgotten_password', [
            'email' => 'user@example.com',
        ]);


        $user = $I->grabEntityFromRepository(User::class, array('email' => 'user@example.com'));

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/user/reset_password/' . $user->getForgottenPasswordToken(), [
            'newPassword'             => 'Password2',
            'newPasswordConfirmation' => 'password'
        ]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $I->seeResponseContains('{"error":{"password":"Password does not match the password confirmation."}}');
    }
}
