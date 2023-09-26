<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

class Version20230926160105 extends AbstractMigration
{
    private string $tableName = 'survey';

    public function getDescription(): string
    {
        return "Create $this->tableName table";
    }

    /**
     * @inheritDoc
     */
    public function up(Schema $schema): void
    {
        $table = $schema->createTable($this->tableName);
        $table->addColumn('id', Types::STRING)->setLength(36)->setNotnull(true);
        $table->addColumn('created_on', Types::DATETIME_IMMUTABLE)->setDefault('CURRENT_TIMESTAMP');

        $table->setPrimaryKey(['id']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable($this->tableName);
    }
}