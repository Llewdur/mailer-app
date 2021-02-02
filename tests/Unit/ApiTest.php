<?php

namespace Tests\Unit;

use App\Jobs\EmailJob;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ApiTest extends TestCase
{
    use WithoutMiddleware;
    use WithFaker;

    public const ENDPOINT_SEND = '/api/send';

    public const ENDPOINT_LIST = '/api/list';

    public const ENDPOINT_LIST_WITH_ATTACHMENTS = '/api/list-with-attachments';

    public function testMissingBodyFails()
    {
        Queue::fake();

        $dataArray = [
            'subject' => $this->faker->word(),
            'to' => $this->faker->unique()->safeEmail,
        ];

        $this->json('POST', self::ENDPOINT_SEND, $dataArray)->assertStatus(422);

        Queue::assertNothingPushed('default', EmailJob::class);
    }

    public function testMissingSubjectFails()
    {
        Queue::fake();

        $dataArray = [
            'body' => $this->faker->paragraph(),
            'to' => $this->faker->unique()->safeEmail,
        ];

        $this->json('POST', self::ENDPOINT_SEND, $dataArray)->assertStatus(422);

        Queue::assertNothingPushed('default', EmailJob::class);
    }

    public function testMissingToFails()
    {
        Queue::fake();

        $dataArray = [
            'body' => $this->faker->paragraph(),
            'subject' => $this->faker->word(),
        ];

        $this->json('POST', self::ENDPOINT_SEND, $dataArray)->assertStatus(422);

        Queue::assertNothingPushed('default', EmailJob::class);
    }

    public function testSendWithoutAttachments()
    {
        Queue::fake();

        $dataArray = [
            'body' => $this->faker->paragraph(),
            'subject' => $this->faker->word(),
            'to' => $this->faker->unique()->safeEmail,
        ];

        $this->json('POST', self::ENDPOINT_SEND, $dataArray)->assertCreated();

        Queue::assertPushed(EmailJob::class);
        Queue::assertPushedOn('default', EmailJob::class);
    }

    public function testSendWithOneAttachment()
    {
        Queue::fake();

        $dataArray = [
            'attachments' => [
                [
                    'name' => date('YmdHis') . '.pdf',
                    'base64' => base64_encode(file_get_contents(storage_path('app/public/CV.Llewellyn du Randt.pdf'))),
                ],
            ],
            'body' => $this->faker->paragraph(),
            'subject' => $this->faker->word(),
            'to' => $this->faker->unique()->safeEmail,
        ];

        $this->json('POST', self::ENDPOINT_SEND, $dataArray)->assertCreated();

        Queue::assertPushed(EmailJob::class);
        Queue::assertPushedOn('default', EmailJob::class);
    }

    public function testSendWithMultipleAttachments()
    {
        Queue::fake();

        $dataArray = [
            'attachments' => [
                [
                    'name' => date('YmdHis') . '.pdf',
                    'base64' => base64_encode(file_get_contents(storage_path('app/public/CV.Llewellyn du Randt.pdf'))),
                ],
                [
                    'name' => date('YmdHis') . '.png',
                    'base64' => base64_encode(file_get_contents(storage_path('app/public/base64.png'))),
                ],
            ],
            'body' => $this->faker->paragraph(),
            'subject' => $this->faker->word(),
            'to' => $this->faker->unique()->safeEmail,
        ];

        $this->json('POST', self::ENDPOINT_SEND, $dataArray)->assertCreated();

        Queue::assertPushed(EmailJob::class);
        Queue::assertPushedOn('default', EmailJob::class);
    }

    public function testList()
    {
        $this->get(self::ENDPOINT_LIST)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'body',
                        'subject',
                        'attachments',
                        // => [
                        //     '*' => [
                        //         'name',
                        //         'download_url',
                        //     ],
                        // ],
                    ],
                ],
            ])
            ->assertOk();
    }
}
