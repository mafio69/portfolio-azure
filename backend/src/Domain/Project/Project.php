<?php

declare(strict_types=1);

namespace App\Domain\Project;

class Project implements \JsonSerializable
{
    private int $id;
    private string $name;
    private string $description;
    private string $url;

    public function __construct(int $id, string $name, string $description, string $url = '')
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->url = $url;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'url' => $this->url
        ];
    }
}