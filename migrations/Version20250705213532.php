<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250705213532 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE `order` ADD customer_name VARCHAR(100) NOT NULL, ADD customer_phone VARCHAR(20) NOT NULL, ADD customer_email VARCHAR(100) NOT NULL, ADD notes LONGTEXT DEFAULT NULL, ADD order_number VARCHAR(50) DEFAULT NULL, ADD pickup_date_time DATETIME DEFAULT NULL, ADD total_ht_cents INT DEFAULT NULL, ADD total_tva_cents INT DEFAULT NULL, ADD total_ttc_cents INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_F5299398551F0F81 ON `order` (order_number)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_item ADD unit_price_ht_cents INT DEFAULT NULL, ADD total_ht_cents INT DEFAULT NULL, ADD total_tva_cents INT DEFAULT NULL, ADD total_ttc_cents INT DEFAULT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_F5299398551F0F81 ON `order`
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `order` DROP customer_name, DROP customer_phone, DROP customer_email, DROP notes, DROP order_number, DROP pickup_date_time, DROP total_ht_cents, DROP total_tva_cents, DROP total_ttc_cents
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_item DROP unit_price_ht_cents, DROP total_ht_cents, DROP total_tva_cents, DROP total_ttc_cents
        SQL);
    }
}
