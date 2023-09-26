<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

class Version20230926180159 extends AbstractMigration
{
    private string $tableName = 'survey_answer';

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
        $table->addColumn('survey_id', Types::STRING)->setLength(36)->setNotnull(true);
        $table->addColumn('answers', Types::JSON)->setNotnull(true);
        $table->addColumn('created_on', Types::DATETIME_IMMUTABLE)->setDefault('CURRENT_TIMESTAMP');
        $table->addColumn('updated_on', Types::DATETIME_MUTABLE)->setDefault('CURRENT_TIMESTAMP');

        $table->setPrimaryKey(['id']);
        $table->addForeignKeyConstraint('survey', ['survey_id'], ['id'], name: 'fk_survey_question_survey_id');
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable($this->tableName);
    }
}