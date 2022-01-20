<?php

namespace ParamConverterBundle\Traits;

use Symfony\Component\HttpFoundation\Request;

trait SafeLoadFieldsTrait
{
    abstract public function getSafeFields(): array;

    /**
     * @throws \JsonException
     */
    public function loadFromJsonString(string $json): void
    {
        if ($json) {
            $array = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        }

        $fields = $array ?? $_POST;

        $this->loadFromArray($fields);
    }

    public function loadFromJsonRequest(Request $request): void
    {
        $this->loadFromJsonString($request->getContent());
    }

    public function loadFromArray(?array $input): void
    {
        if (empty($input)) {
            return;
        }
        $safeFields = $this->getSafeFields();
        foreach ($safeFields as $field) {
            if (array_key_exists($field, $input)) {
                $this->{$field} = $input[$field];
            }
        }
    }
}