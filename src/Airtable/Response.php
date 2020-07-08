<?php declare(strict_types=1);

namespace Airtable;

/**
 * Airtable API Response class
 */
class Response // implements \ArrayAccess
{
    /** @var \Airtable\Client */
    private $client;

    /** @var string Raw response content */
    private $content;

    /** @var array Parsed response content */
    private $parsedContent;

    public function __construct($client, $request, $content) 
    {
        $this->client = $client;
        $this->request = $request;
        $this->content = $content;
        
        try {
            $this->parsedContent = json_decode($content, true);
        } catch(\Exception $e) {
            $this->parsedContent = false; // ?
        }


    }
    
    // TODO: Loading relations

    public function __toString()
    {
        return $this->content;
    }

    /**
     * 
     * @return \Airtable\Record
     */
    public function getRecord()
    {
        return new \Airtable\Record(
            $this->parsedContent['id'], 
            $this->parsedContent['fields']
        );
    }

    /**
     * 
     * @return \Airtable\Record[]
     */
    public function getRecords()
    {
        return array_map(function(array $value) {
            return new \Airtable\Record($value['id'], $value['fields']);
        }, $this->parsedContent['records']);
    }
}
