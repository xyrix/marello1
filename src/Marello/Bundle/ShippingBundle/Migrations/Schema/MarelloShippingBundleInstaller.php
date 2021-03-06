<?php

namespace Marello\Bundle\ShippingBundle\Migrations\Schema;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\AttachmentBundle\Migration\Extension\AttachmentExtension;
use Oro\Bundle\AttachmentBundle\Migration\Extension\AttachmentExtensionAwareInterface;
use Oro\Bundle\MigrationBundle\Migration\Installation;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

/**
 * @SuppressWarnings(PHPMD.TooManyMethods)
 * @SuppressWarnings(PHPMD.ExcessiveClassLength)
 */
class MarelloShippingBundleInstaller implements Installation, AttachmentExtensionAwareInterface
{
    /**
     * @var AttachmentExtension
     */
    protected $attachmentExtension;

    /**
     * {@inheritdoc}
     */
    public function getMigrationVersion()
    {
        return 'v1_1';
    }

    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        /** Tables generation **/
        $this->createMarelloShipmentTable($schema);
        
        $this->addMarelloShipmentForeignKeys($schema);

        $this->attachmentExtension->addImageRelation(
            $schema,
            'marello_shipment', // entity table, e.g. oro_user, orocrm_contact etc.
            'shipping_label', // field name
            [], //additional options for relation
            20, // max allowed file size in megabytes, can be omitted, by default 1 Mb
            200, // thumbnail width in pixels, can be omitted, by default 32
            200 // thumbnail height in pixels, can be omitted, by default 32
        );
    }

    /**
     * Create marello_shipment table
     *
     * @param Schema $schema
     */
    protected function createMarelloShipmentTable(Schema $schema)
    {
        $table = $schema->createTable('marello_shipment');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('shipping_service', 'string', ['length' => 255]);
        $table->addColumn('ups_shipment_digest', 'text', ['notnull' => false]);
        $table->addColumn('base64_encoded_label', 'text', ['notnull' => false]);
        $table->addColumn('identification_number', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('ups_package_tracking_number', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('organization_id', 'integer', ['notnull' => false]);
        $table->addColumn('created_at', 'datetime', []);
        $table->addColumn('updated_at', 'datetime', ['notnull' => false]);
        $table->setPrimaryKey(['id']);
    }

    /**
     * Add marello_shipment foreign keys.
     *
     * @param Schema $schema
     */
    protected function addMarelloShipmentForeignKeys(Schema $schema)
    {
        $table = $schema->getTable('marello_shipment');
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_organization'),
            ['organization_id'],
            ['id'],
            ['onDelete' => 'SET NULL', 'onUpdate' => null]
        );
    }

    /**
     * Sets the AttachmentExtension
     *
     * @param AttachmentExtension $attachmentExtension
     */
    public function setAttachmentExtension(AttachmentExtension $attachmentExtension)
    {
        $this->attachmentExtension = $attachmentExtension;
    }
}
