<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

class Version20230926160832 extends AbstractMigration
{
    private string $tableName = 'survey_question';

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
        $table->addColumn('title', Types::STRING)->setLength(240)->setNotnull(true);
        $table->addColumn('variants', Types::JSON)->setNotnull(true);
        $table->addColumn('correct_variants', Types::JSON)->setNotnull(true);
        $table->addColumn('answer_condition', Types::STRING)->setLength(32)->setNotnull(true);
        $table->addColumn('created_on', Types::DATETIME_IMMUTABLE)->setDefault('CURRENT_TIMESTAMP');

        $table->setPrimaryKey(['id']);
        $table->addForeignKeyConstraint('survey', ['survey_id'], ['id'], name: 'fk_survey_question_survey_id');
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable($this->tableName);
    }
}