<?php

declare(strict_types=1);

namespace App\Tests\UI\Http\Rest\Response;

use App\Application\Query\Collection;
use App\Application\Query\Item;
use App\Domain\User\ValueObject\Email;
use App\Domain\User\ValueObject\Uuid;
use App\Infrastructure\User\Query\Projections\UserView;
use App\UI\Http\Rest\Response\JsonApiFormatter;
use PHPUnit\Framework\TestCase;

class JsonApiFormatterTest extends TestCase
{
    /**
     * @test
     *
     * @group unit
     *
     * @throws \Exception
     * @throws \Assert\AssertionFailedException
     */
    public function format_collection(): void
    {
        $users = [
            self::createUserView(Uuid::uuid4(), Email::fromString('asd1@asd.asd')),
            self::createUserView(Uuid::uuid4(), Email::fromString('asd2@asd.asd')),
        ];

        $response = JsonApiFormatter::collection(new Collection(1, 10, \count($users), $users));

        self::assertArrayHasKey('data', $response);
        self::assertArrayHasKey('meta', $response);
        self::assertArrayHasKey('total', $response['meta']);
        self::assertArrayHasKey('page', $response['meta']);
        self::assertArrayHasKey('size', $response['meta']);
        self::assertCount(2, $response['data']);
    }

    /**
     * @test
     *
     * @group unit
     *
     * @throws \Assert\AssertionFailedException
     * @throws \Exception
     */
    public function format_one_output(): void
    {
        $userView = self::createUserView(Uuid::uuid4(), Email::fromString('demo@asd.asd'));

        $response = JsonApiFormatter::one(new Item($userView));

        self::assertArrayHasKey('data', $response);
        self::assertSame('UserView', $response['data']['type']);
        self::assertCount(2, $response['data']['attributes']);
    }

    /**
     * @throws \Assert\AssertionFailedException
     */
    private static function createUserView(Uuid $uuid, Email $email): UserView
    {
        $view = UserView::deserialize([
            'uuid'        => $uuid->toString(),
            'credentials' => [
                'email'    => $email->toString(),
                'password' => 'ljalsjdlajsdljlajsd',
            ],
        ]);

        return $view;
    }
}
