<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210613184004 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Creates schedule_detail table';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('
            CREATE TABLE `schedule_detail` (
                id CHAR(36) NOT NULL PRIMARY KEY,
                schedule_id CHAR(36) NOT NULL,
                day VARCHAR(9) NOT NULL,
                start_hour DATETIME NOT NULL,
                end_hour DATETIME NOT NULL,
                INDEX IDX_schedule_detail_schedule_id (schedule_id),
                CONSTRAINT FK_schedule_detail_schedule_id FOREIGN KEY (schedule_id) REFERENCES `schedule` (id) ON UPDATE CASCADE ON DELETE CASCADE
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB'
        );
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP TABLE `schedule_detail`');
    }
}
