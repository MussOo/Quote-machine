<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MailTest extends WebTestCase
{
    public function testRegisterMail(): void
    {
        $client = static::createClient();

        $client->request('GET', '/register');

        $client->submitForm('Register', [
            'registration_form[name]' => "john",
            'registration_form[email]' => "john@exemple.com",
            'registration_form[plainPassword]' => "TestTTT24324eié",
        ]);

        $this->assertQueuedEmailCount(1);

        // récupération du premier email
        $email = $this->getMailerMessage();

        $this->assertEmailAddressContains($email, 'To', 'john@exemple.com');
        $this->assertEmailTextBodyContains($email, "Merci d'avoir rejoint la quote machine");
        // $this->assertEmailAttachmentCount($email, 1);

        $client->followRedirect();
        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('quote_index');


    }
}
