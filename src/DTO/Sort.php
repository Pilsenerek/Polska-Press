<?php

namespace App\DTO;

class Sort {
    
    /**
     * @var array
     */
    private $urls = [];

    /**
     * @var string
     */
    private $order;

    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $field;

    /**
     * @param array $sortUrls
     * @param string $order
     * @param string $key
     * @param string $field
     */
    public function __construct(array $sortUrls, $order, $key, $field) {
        $this->urls = $sortUrls;
        $this->order = $order;
        $this->key = $key;
        $this->field = $field;
    }

    /**
     * @return array
     */
    public function getUrls(): array {

        return $this->urls;
    }

    /**
     * @param array $urls
     */
    public function setUrls(array $urls) {
        $this->urls = $urls;
    }

    /**
     * @return string
     */
    public function getOrder(): string {

        return $this->order;
    }

    /**
     * @return string
     */
    public function getKey(): string {

        return $this->key;
    }

    /**
     * @return string
     */
    public function getField(): string {

        return $this->field;
    }

    /**
     * @param string $order
     */
    public function setOrder(string $order) {
        $this->order = $order;
    }

    /**
     * @param string $key
     */
    public function setKey(string $key) {
        $this->key = $key;
    }

    /**
     * @param string $field
     */
    public function setField(string $field) {
        $this->field = $field;
    }

}
