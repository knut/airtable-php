<?php declare(strict_types=1);

namespace Airtable;

/**
 * Airtable API Request class
 */
class Request
{
    /** @var string $tableName Name of the Airtable table */
    public $tableName;

    /** @var \Airtable\Client $client */
    public $client; // 

    public function __construct($client, $tableName)
    {
        $this->client = $client;
        $this->tableName = $tableName;
    }

    public function findById($id)
    {
        try {
            $response = $this->client->getHttpClient()->get($this->getEndpointUri().'/'.$id);
            $contents = $response->getBody()->getContents();
        } catch(\GuzzleHttp\Exception\RequestException $e) {
            self::handleException($e);
        }

        $res = new \Airtable\Response($this->client, $this, $contents);
        return $res->getRecord();
    }

    /**
     * 
     * Returned records do not include any fields with "empty" values, e.g. "", [], or false.
     * 
     * @param string|string[] Only data for fields whose names are in this list will be included in the result. If you don't need every field, you can use this parameter to reduce the amount of data transferred.
     */
    public function find($params = [])
    {
        if(is_string($params)) { // find by id
            return $this->findById($params);
        }

        try {
            $response = $this->client->getHttpClient()->get($this->getEndpointUri(), ['query' => $params]);
            $contents = $response->getBody()->getContents();
        } catch(\GuzzleHttp\Exception\RequestException $e) {
            self::handleException($e);
        }
        self::checkForError($contents);

        // TODO: pagination
        // "offset=itrqzlmjIQAfsHvoc/rec602nRQTE3HyCbY" <- next page of data

        $res = new \Airtable\Response($this->client, $this, $contents);
        return $res->getRecords();
    }

    public function findAll()
    {
        // Fetch all (even > 100)
    }

    /**
     * Create new records
     * 
     * @param array @params Can be one or multiple records
     */
    public function create(array $params) 
    {
        $body = ['records' => []];
        if(self::isMultiArray($params)) {
            foreach($params as $record) {
                $body['records'][] = ['fields' => $record];
            }
        } else {
            $body['records'] = ['records' => [['fields' => $params]]];
        }

        $body = json_encode($body);

        try {
            $response = $this->client->getHttpClient()->post($this->getEndpointUri(), ['body' => $body]);
            $contents = $response->getBody()->getContents();
        } catch(\GuzzleHttp\Exception\RequestException $e) {
            self::handleException($e);
        }
        self::checkForError($contents);

        $res = new \Airtable\Response($this->client, $this, $contents);
        $records = $res->getRecords();
        if(count($records) == 1) {
            return $records[0];
        } else {
            return $records;
        }

    }

    /**
     * Only update the fields you specify, leaving the rest as they were
     * 
     * @param \Airtable\Record|\Airtable\Record[]
     */
    public function update($records)
    {
        $data = [];
        if($records instanceof \Airtable\Record) {
            $data['records'][] = $records->toArray();
        } else {
            foreach($records as $record) {
                $data['records'][] = $record->toArray();
            }
        }
        $body = json_encode($data);

        try {
            $response = $this->client->getHttpClient()->patch($this->getEndpointUri(), ['body' => $body]);
            $contents = $response->getBody()->getContents();
        } catch(\GuzzleHttp\Exception\RequestException $e) {
            self::handleException($e);
        }
        self::checkForError($contents);
        
        // TODO: Return new Record-object
        $res = new \Airtable\Response($this->client, $this, $contents);
        $records = $res->getRecords();
        if(count($records) == 1) {
            return $records[0];
        } else {
            return $records;
        }


    }

    /**
     * 
     * @param \Airtable\Record $record
     */
    public function delete($record)
    {
        if($record instanceof \Airtable\Record) {
            $record_id = $record->getId();
        } else {
            $record_id = $record;
        }

        try {
            $response = $this->client->getHttpClient()->delete($this->getEndpointUri().'/'.$record_id);
            $contents = $response->getBody()->getContents();
        } catch(\GuzzleHttp\Exception\RequestException $e) {
            self::handleException($e);
        }
        self::checkForError($contents);

        $body = json_decode($contents, true);
        if($body['id'] == $record_id && $body['deleted'] == true) {
            return true;
        } else {
            return false;
        }

    }
    
    public function getEndpointUri()
    {
        return $this->client->getBaseUri().'/'.$this->tableName;
    }

    private static function checkForError($json) {
        $body = json_decode($json, true);
        if(isset($body['error'])) {
            throw new \Airtable\Exception\ApiErrorException($body['error']['message']);
        }
    }

    private static function handleException($e) {
        $reason = $e->getResponse()->getReasonPhrase();
        $code = $e->getResponse()->getStatusCode();

        switch($code) {
            case 422:
                throw new \Airtable\Exception\InvalidRequestException($reason, $code, $e);
            default:
                throw new \Airtable\Exception\ApiErrorException($reason, $code, $e);
        }
    }

    private static function isMultiArray($array) {
        rsort($array);
        return isset($array[0]) && is_array($array[0]);
    }
}