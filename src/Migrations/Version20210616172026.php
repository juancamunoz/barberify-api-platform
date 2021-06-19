<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210616172026 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Creates the `appointment` table';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('
            CREATE TABLE `appointment` (
                id CHAR(36) NOT NULL PRIMARY KEY,
                owner_id CHAR(36) NOT NULL,
                enterprise_id CHAR(36) NOT NULL,
                schedule_id CHAR(36) NOT NULL,
                date DATETIME NOT NULL,
                duration INTEGER NOT NULL,
                created_at DATETIME NOT NULL,
                updated_at DATETIME NOT NULL,
                INDEX IDX_appointment_schedule_id (schedule_id),
                INDEX IDX_appointment_owner_id (owner_id),
                INDEX IDX_appointment_enterprise_id (enterprise_id),
                CONSTRAINT FK_appointment_owner_id FOREIGN KEY (owner_id) REFERENCES `user` (id) ON UPDATE CASCADE ON DELETE CASCADE,
                CONSTRAINT FK_appointment_enterprise_id FOREIGN KEY (enterprise_id) REFERENCES `enterprise` (id) ON UPDATE CASCADE ON DELETE CASCADE,
                CONSTRAINT FK_appointment_schedule_id FOREIGN KEY (schedule_id) REFERENCES `schedule` (id) ON UPDATE CASCADE ON DELETE CASCADE
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB'
        );
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP TABLE `appointment`');
    }
}
