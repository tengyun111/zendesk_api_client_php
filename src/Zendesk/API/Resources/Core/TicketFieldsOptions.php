<?php

namespace Zendesk\API\Resources\Core;

use Zendesk\API\Exceptions\CustomException;
use Zendesk\API\Exceptions\MissingParametersException;
use Zendesk\API\Http;
use Zendesk\API\Resources\ResourceAbstract;
use Zendesk\API\Traits\Resource\Defaults;

/**
 * The TicketFieldsOptions class exposes options methods for ticketsFields
 */
class TicketFieldsOptions extends ResourceAbstract
{
    use Defaults {
        findAll as traitFindAll;
        find as traitFind;
        create as traitCreate;
        update as traitUpdate;
        delete as traitDelete;
    }

    /**
     * {@inheritdoc}
     */
    protected $objectName = 'custom_field_option';

    /**
     * {@inheritdoc}
     */
    protected $objectNamePlural = 'custom_field_options';

    /**
     * {@inheritdoc}
     */
    protected function setUpRoutes()
    {
        $this->setRoutes(
            [
                'findAll'   => 'ticket_fields/{fieldId}/options.json',
                'find'      => 'ticket_fields/{fieldId}/options/{id}.json',
                'create'    => 'ticket_fields/{fieldId}/options.json',
                'update'    => 'ticket_fields/{fieldId}/options.json',
                'delete'    => 'ticket_fields/{fieldId}/options/{id}.json',
            ]
        );
    }

    /**
     * Get the fieldId passed as a parameter or as a chained parameter
     *
     * @param array $params
     *
     * @throws MissingParametersException
     */
    private function addTicketFieldIdToRouteParams(array $params)
    {
        if (isset($params['fieldId'])) {
            $fieldId = $params['fieldId'];
        } else {
            $fieldId = $this->getChainedParameter(TicketFields::class);
        }

        if (empty($fieldId)) {
            throw new MissingParametersException(__METHOD__, ['fieldId']);
        }

        $this->setAdditionalRouteParams(['fieldId' => $fieldId]);
    }

    /**
     * Returns all options for a particular ticket field
     *
     * @param array $params
     *
     * @return \stdClass | null
     * @throws MissingParametersException
     */
    public function findAll(array $params = [])
    {
        $this->addTicketFieldIdToRouteParams($params);

        return $this->traitFindAll($params);
    }

    /**
     * Show a specific option record
     *
     * @param null|int $id
     * @param array $params
     * @return null|\stdClass
     * @throws MissingParametersException
     */
    public function find($id = null, array $params = [])
    {
        if (empty($id)) {
            $id = $this->getChainedParameter(get_class($this));
        }

        $this->addTicketFieldIdToRouteParams($params);

        return $this->traitFind($id);
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $params = [])
    {
        $this->addTicketFieldIdToRouteParams($params);

        $response = Http::send(
            $this->client,
            $this->getRoute(__FUNCTION__),
            [
                'postFields' => [$this->objectName => $params],
                'method'     => 'POST'
            ]
        );

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function update($id = null, array $updateResourceFields = [])
    {

        if (empty($id)) {
            $id = $this->getChainedParameter(get_class($this));
        }

        $updateResourceFields['id'] = $id;

        $this->addTicketFieldIdToRouteParams($updateResourceFields);

        $response = Http::send(
            $this->client,
            $this->getRoute(__FUNCTION__),
            [
                'postFields' => [$this->objectName => $updateResourceFields],
                'method'     => 'POST'
            ]
        );

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function delete($id = null)
    {
        $this->addTicketFieldIdToRouteParams([]);

        return $this->traitDelete($id);
    }
}
