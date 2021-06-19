<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210611091115 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Creates the schedule table';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('
            CREATE TABLE `schedule` (
                id CHAR(36) NOT NULL PRIMARY KEY,
                enterprise_id CHAR(36) NOT NULL,
                date_from DATETIME NOT NULL,
                date_to DATETIME NOT NULL,
                interval_time INTEGER NOT NULL,
                INDEX IDX_schedule_enterprise_id (enterprise_id),
                CONSTRAINT FK_schedule_enterprise_id FOREIGN KEY (enterprise_id) REFERENCES `enterprise` (id) ON UPDATE CASCADE ON DELETE CASCADE
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB'
        );
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP TABLE `schedule`');
    }
}
