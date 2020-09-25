<?php namespace App\Tests\Controller;
use App\Entity\User;
use App\Tests\ApiTester;

class UserDetailsCest
{
    public function _before(ApiTester $I)
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

    public function addExtraUserDetailsOnSuccessTest(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPATCH('/user/extra_details', [
            'firstname' => 'Firstname',
            'surname'   => 'Surname',
        ]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseContains('{"success":"User details successfully added."}');

        $user = $I->grabEntityFromRepository(User::class, ['email' => 'user@example.com']);
        $I->assertSame('user@example.com', $user->getEmail());
        $I->assertSame('Firstname', $user->getFirstName());
        $I->assertSame('Surname', $user->getSurname());
        $I->assertFalse($user->isClosed());
    }

    public function addExtraUserDetailsOnInvalidFirstnameTest(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPATCH('/user/extra_details', [
            'firstname' => 'a',
            'surname'   => 'Surname',
        ]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $I->seeResponseContains('{"error":{"[firstname]":"This value is too short. It should have 3 characters or more."}}');
    }

    public function addExtraUserDetailsOnInvalidSurnameTest(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPATCH('/user/extra_details', [
            'firstname' => 'Firstname',
            'surname'   => 'a',
        ]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $I->seeResponseContains('{"error":{"[surname]":"This value is too short. It should have 3 characters or more."}}');
    }

    public function addExtraUserDetailsOnMissingFieldTest(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPATCH('/user/extra_details', [
            'firstname' => 'Firstname',
        ]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $I->seeResponseContains('{"error":{"[surname]":"This field is missing."}}');
    }
}
