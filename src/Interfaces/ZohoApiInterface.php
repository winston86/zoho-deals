<?php

namespace Winston86\ZohoDeals\Interfaces;

interface ZohoApiInterface
{
    public function authenticate(): string;

    public function getRecord(string $module, string $id): array;

    public function createRecord(string $module, array $data): array;

    public function searchRecords(string $module, string $criteria): array;
}