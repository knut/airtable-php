<?php declare(strict_types=1);

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;

final class ClientTest extends \PHPUnit\Framework\TestCase
{

    protected $httpClient;

    protected $mockHandler;

    protected $airtable;

    protected function setUp() : void
    {
        $this->mockHandler = new MockHandler();
        $handlerStack = HandlerStack::create($this->mockHandler);
        $this->httpClient = new Client(['handler' => $handlerStack]);

        $this->airtable = new \Airtable\Client('123', '456');
        $this->airtable->setHttpClient($this->httpClient);
    }

    /**
     * @test
     */
    public function can_find_records()
    {
        $this->mockHandler->append(new Response(200, [], file_get_contents(__DIR__.'/fixtures/records.json')));

        $records = $this->airtable->companies->find();
        $this->assertCount(3, $records);
    }

    /**
     * @test
     */
    public function can_find_record_by_id()
    {
        $this->mockHandler->append(new Response(200, [], file_get_contents(__DIR__.'/fixtures/record.json')));

        $record = $this->airtable->companies->find('rec3APJV3yRHlpHoA');
        $this->assertEquals('rec3APJV3yRHlpHoA', $record->getId());
    }

    /**
     * @test
     */
    public function can_find_records_and_sort_with_sort_objects()
    {
        $this->mockHandler->append(new Response(200, [], file_get_contents(__DIR__.'/fixtures/records-sorted.json')));

        $records = $this->airtable->companies->find(['sort' => '[{field: "Name", direction: "desc"}]']);
        $this->assertCount(3, $records);
        $this->assertEquals('rec602nRQTE3HyCbY', $records[0]->getId());
    }

    /**
     * @test
     */
    public function can_find_records_by_view()
    {
        $this->mockHandler->append(new Response(200, [], file_get_contents(__DIR__.'/fixtures/records-sorted.json')));

        $records = $this->airtable->companies->find(['view' => 'Named View']);
        $this->assertCount(3, $records);
        $this->assertEquals('rec602nRQTE3HyCbY', $records[0]->getId());
    }

    /**
     * @test
     */
    public function can_find_records_by_filtering()
    {
        $this->mockHandler->append(new Response(200, [], file_get_contents(__DIR__.'/fixtures/records-sorted.json')));

        $records = $this->airtable->companies->find(['filterByFormula' => "NOT({website_url} = '')"]);
        $this->assertCount(3, $records);
        $this->assertEquals('rec602nRQTE3HyCbY', $records[0]->getId());
    }

    /**
     * @test
     */
    public function can_find_records_with_selected_fields()
    {
        $this->mockHandler->append(new Response(200, [], file_get_contents(__DIR__.'/fixtures/records.json')));

        $params = [
            'fields' => ['name', 'website_url']
        ];
        $records = $this->airtable->companies->find($params);

        $recordFields = array_keys($records[0]->getFields());
        sort($params['fields']);
        sort($recordFields);

        $this->assertEquals($params['fields'], $recordFields);
    }

    /**
     * @test
     */
    public function can_create_record()
    {
        $this->mockHandler->append(new Response(200, [], file_get_contents(__DIR__.'/fixtures/create-record-response.json')));

        $fields = [
            'name' => 'Basecamp',
            'website_url' => 'https://basecamp.com',
        ];
        $record = $this->airtable->companies->create($fields);

        $this->assertEquals($fields['name'], $record['name']);
        $this->assertEquals($fields['website_url'], $record['website_url']);
    }

    /**
     * @test
     */
    public function can_create_multiple_records()
    {
        $this->mockHandler->append(new Response(200, [], file_get_contents(__DIR__.'/fixtures/create-multiple-records-response.json')));

        $companies = [
            [
                'name' => 'Apple',
                'website_url' => 'https://apple.com',
            ],
            [
                'name' => 'Google',
                'website_url' => 'https://google.com',
            ]
        ];        
        $records = $this->airtable->companies->create($companies);

        $this->assertEquals($companies[0]['name'], $records[0]['name']);
        $this->assertEquals($companies[0]['website_url'], $records[0]['website_url']);
        $this->assertEquals($companies[1]['name'], $records[1]['name']);
        $this->assertEquals($companies[1]['website_url'], $records[1]['website_url']);
    }

    /**
     * @test
     */
    public function can_update_one_record()
    {
        $this->mockHandler->append(new Response(200, [], file_get_contents(__DIR__.'/fixtures/create-record-response.json')));
        $this->mockHandler->append(new Response(200, [], file_get_contents(__DIR__.'/fixtures/update-record-response.json')));

        $fields = [
            'name' => 'Basecamp',
        ];
        $record = $this->airtable->companies->create($fields);
        $this->assertEquals($fields['name'], $record['name']);

        $record['name'] = 'Updated Basecamp';
        $updatedRecord = $this->airtable->companies->update($record);
        $this->assertEquals($record['name'], $updatedRecord['name']);
    }

    /**
     * @test
     */
    public function can_update_multiple_records()
    {
        $this->markTestIncomplete();
    }

    /**
     * @test
     */
    public function can_delete_one_record()
    {
        $this->mockHandler->append(new Response(200, [], file_get_contents(__DIR__.'/fixtures/record.json')));
        $this->mockHandler->append(new Response(200, [], file_get_contents(__DIR__.'/fixtures/delete-record-response.json')));

        $record = $this->airtable->companies->find('rec3APJV3yRHlpHoA');
        $this->assertEquals('rec3APJV3yRHlpHoA', $record->getId());
        
        $response = $this->airtable->companies->delete($record);
        $this->assertTrue($response);
    }

    /**
     * @test
     */
    public function can_delete_multiple_records()
    {
        $this->markTestIncomplete();
    }

    /**
     * @test
     */
    public function can_catch_bad_request_error()
    {
        $this->markTestIncomplete();
    }

    /**
     * @test
     */
    public function can_catch_payment_required_error()
    {
        $this->markTestIncomplete();
    }

    /**
     * @test
     */
    public function can_catch_not_found_error()
    {
        $this->markTestIncomplete();
    }

    /**
     * @test
     */
    public function can_catch_request_entity_too_large_error()
    {
        $this->markTestIncomplete();
    }

    /**
     * @test
     */
    public function can_catch_invalid_request_error()
    {
        $this->mockHandler->append(new Response(200, [], file_get_contents(__DIR__.'/fixtures/error-422-invalid-request.json')));

        $this->expectException(\Airtable\Exception\ApiErrorException::class);

        $records = $this->airtable->companies->find([
            'filterByFormula' => "FOOBAR"
        ]);
    }

    /**
     * @test
     */
    public function can_catch_internal_server_error()
    {
        $this->markTestIncomplete();
    }

    /**
     * @test
     */
    public function can_catch_bad_gateway_error()
    {
        $this->markTestIncomplete();
    }

    /**
     * @test
     */
    public function can_catch_service_unavailable()
    {
        $this->markTestIncomplete();
    }

}
