<?php

namespace OZiTAG\Tager\Backend\Export\Contracts;

use OZiTAG\Tager\Backend\Utils\Helpers\ArrayHelper;

abstract class BaseExportStrategy
{
    abstract function getId(): string;

    abstract function getName(): string;

    abstract function getHeader(): array;

    abstract function getFileName(): string;

    abstract function execute(): void;

    private ?string $message = null;

    private array $payload;

    private array $data = [];

    public function conditionalFields(): array
    {
        return [];
    }

    public function setPayload(?array $payload = null)
    {
        $this->payload = $payload ?? [];
    }

    protected function add(array $row)
    {
        if (ArrayHelper::isAssoc($row)) {
            $header = $this->getHeader();
            if ($header) {
                $resultRow = [];
                foreach ($header as $param => $label) {
                    if (array_key_exists($param, $row)) {
                        $resultRow[] = $row[$param];
                    } else {
                        $resultRow[] = '';
                    }
                }
                $this->data[] = $resultRow;
            }
        } else {
            $this->data[] = $row;
        }
    }

    public function reset(){
        $this->data = [];
    }

    public function getData(): array
    {
        return $this->data;
    }

    protected function setMessage(string $message)
    {
        $this->message = $message;
    }

    protected function getParam(string $param): mixed
    {
        return array_key_exists($param, $this->payload) ? $this->payload[$param] : null;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }
}
