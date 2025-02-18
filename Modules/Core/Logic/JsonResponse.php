<?php

namespace Modules\Core\Logic;

use Exception;

class JsonResponse
{
    protected string $name;
    protected object $error;

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /*TODO: Refactor, implement Enums, maybe matching*/
    public function delete(string $type): string
    {
        if ($type === 'success') {
            return json_encode(
                ['message' => strtoupper($this->name) . ' removed with success',
                    'type' => 'success']);
        } elseif ($type === 'error') {
            return json_encode(
                ['message' => strtoupper($this->name) . ' was not removed',
                    'type' => 'error']);
        }
        return json_encode(
            ['message' => 'type was not specified (example: success|error)',
                'type' => 'api_error']);
    }
}
