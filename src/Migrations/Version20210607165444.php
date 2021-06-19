<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210607165444 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Creates enterprise table';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE TABLE `enterprise` (
            id CHAR(36) NOT NULL PRIMARY KEY,
            owner_id CHAR(36) NOT NULL,            
            name VARCHAR(100) NOT NULL,
            location VARCHAR(255) NOT NULL,
            avatar VARCHAR(255) DEFAULT NULL,
            INDEX IDX_enterprise_owner_id (owner_id),
            CONSTRAINT FK_enterprise_owner_id FOREIGN KEY (owner_id) REFERENCES `user` (id) ON UPDATE CASCADE ON DELETE CASCADE
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP TABLE `enterprise`');
    }
}
