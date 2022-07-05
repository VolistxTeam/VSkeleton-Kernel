<?php

namespace Volistx\FrameworkKernel\DataTransferObjects;

class PlanDTO extends DataTransferObjectBase
{
    public string $id;
    public string $name;
    public ?string $description;
    public array $data;
    public float $price;
    public bool $custom;
    public string $created_at;
    public string $updated_at;

    public static function fromModel($plan): self
    {
        return new self($plan);
    }

    public function GetDTO(): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'description' => $this->description,
            'data'        => $this->data,
            'price'       => $this->price,
            'custom'      => $this->custom,
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
        ];
    }
}
