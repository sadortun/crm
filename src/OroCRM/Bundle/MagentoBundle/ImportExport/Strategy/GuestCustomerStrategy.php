<?php

namespace OroCRM\Bundle\MagentoBundle\ImportExport\Strategy;

use OroCRM\Bundle\MagentoBundle\Entity\Customer;

class GuestCustomerStrategy extends AbstractImportStrategy
{
    /**
     * ID of group for not logged customers
     */
    const NOT_LOGGED_IN_ID = 0;

    /**
     * {@inheritdoc}
     */
    public function process($entity)
    {
        $this->assertEnvironment($entity);

        if ($this->checkExistingCustomer($entity)) {
            return null;
        }

        $this->cachedEntities = [];
        $entity = $this->beforeProcessEntity($entity);
        $entity = $this->processEntity($entity, true, true, $this->context->getValue('itemData'));
        $entity = $this->afterProcessEntity($entity);
        if ($entity) {
            $entity = $this->validateAndUpdateContext($entity);
        }

        return $entity;
    }

    /**
     * @param Customer $entity
     *
     * @return Customer
     */
    protected function checkExistingCustomer(Customer $entity)
    {
        if (!$entity->getEmail()) {
            return null;
        }

        $existingCustomer = $this->databaseHelper->findOneBy(
            'OroCRM\Bundle\MagentoBundle\Entity\Customer',
            [
                'email' => $entity->getEmail(),
                'channel' => $entity->getChannel()
            ]
        );

        return $existingCustomer;
    }

    /**
     * @param Customer $entity
     *
     * {@inheritdoc}
     */
    protected function afterProcessEntity($entity)
    {
        $this->processChangeAttributes($entity);

        return parent::afterProcessEntity($entity);
    }

    /**
     * @param Customer $entity
     */
    protected function processChangeAttributes(Customer $entity)
    {
        foreach ($entity->getAddresses() as $address) {
            $address->setOriginId(null);
        }

        $customerStore = $entity->getStore();
        if ($customerStore) {
            $website = $customerStore->getWebsite();

            $entity->setWebsite($website);
            $this->setDefaultGroup($entity);
        }
    }

    /**
     * @param Customer $entity
     */
    protected function setDefaultGroup(Customer $entity)
    {
        if (!$entity->getGroup()) {
            $em = $this->strategyHelper->getEntityManager('OroCRMMagentoBundle:CustomerGroup');
            $group = $em->getRepository('OroCRMMagentoBundle:CustomerGroup')
                ->findOneBy(
                    [
                        'originId' => static::NOT_LOGGED_IN_ID,
                        'channel' => $entity->getChannel()
                    ]
                );
            $entity->setGroup($group);
        }
    }

    /*
     * @param object $entity
     * @param array $searchContext
     * @return null|object|Customer
     */
    protected function findExistingEntity($entity, array $searchContext = [])
    {
        if ($entity instanceof Customer) {
            return $this->checkExistingCustomer($entity);
        }

        return parent::findExistingEntity($entity, $searchContext);
    }
}
