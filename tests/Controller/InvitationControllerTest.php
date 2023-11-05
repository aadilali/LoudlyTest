<?php
// tests/Controller/InvitationControllerTest.php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class InvitationControllerTest extends WebTestCase
{
    public function testSendInvitation()
    {
        $client = static::createClient();

        $invitationData = [
            'sender_id' => 1,
            'invited_id' => 2,
            'description' => 'Test Invitation',
        ];

        $client->request(
            'POST',
            '/api/invitation',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($invitationData)
        );

        $this->assertEquals(Response::HTTP_CREATED, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testCancelInvitation()
    {
        $client = static::createClient();

        $invitationId = 1;

        $invitationData = [
            'invitation_id' => $invitationId,
        ];

        $client->request(
            'PUT',
            '/api/invitation',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($invitationData)
        );

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testRespondInvitation()
    {
        $client = static::createClient();

        $invitationData = [
            'invitation_id' => 1,
            'response' => 'accept',
        ];

        $client->request(
            'PUT',
            '/api/accept_invitation',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($invitationData)
        );

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('Invitation responded successfully', $responseData['message']);
    }

    public function testInvalidRespondInvitation()
    {
        $client = static::createClient();

        $invitationData = [
            'invitation_id' => 1,
            'response' => 'invalid',
        ];

        $client->request(
            'PUT',
            '/api/accept_invitation',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($invitationData)
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('Invalid response', $responseData['message']);
    }

    public function testCancelInvitationNotFound()
    {
        $client = static::createClient();

        $invitationData = [
            'invitation_id' => 222,
        ];

        $client->request(
            'PUT',
            '/api/invitation',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($invitationData)
        );

        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('Invitation not found', $responseData['message']);
    }
}
