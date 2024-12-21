<?php

namespace DiscordBot\Repository;

class ExampleRepository extends SqlLiteDatabaseRepository
{
    public function __construct(string $path)
    {
        parent::__construct($path);

        $this->pdo->exec('CREATE TABLE IF NOT EXISTS example (
            example_id TEXT,
            example_name TEXT,
            PRIMARY KEY (example_id)
        )');
    }

    public function add(string $exampleId, string $example): void
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO example (example_id, example_name)
            VALUES (:example_id, :example_name)
            ON CONFLICT(example_id) DO UPDATE SET example_name = :example_name'
        );

        $stmt->execute([
            'example_id'  => $exampleId,
            'example_name' => $example,
        ]);
    }

    public function remove(string $exampleId): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM example WHERE example_id = :example_id');

        $stmt->execute([
            'example_id'  => $exampleId,
        ]);
    }

    public function get(int $exampleId): ?string
    {
        $stmt = $this->pdo->prepare(
            'SELECT example_name FROM example WHERE example_id = :example_id'
        );

        $stmt->execute([
            'example_id'  => $exampleId,
        ]);

        return (string) $stmt->fetchColumn() ?: null;
    }
}
