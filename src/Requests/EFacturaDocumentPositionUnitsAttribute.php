<?php

declare(strict_types=1);

namespace Konekt\Factureaza\Requests;

use Konekt\Factureaza\Exceptions\InvalidEFacturaDocumentPositionUnitAttribute;
use Konekt\Factureaza\Exceptions\InvalidInvoiceItemException;
use Konekt\Factureaza\Validation\SuperTinyArrayValidator;

class EFacturaDocumentPositionUnitsAttribute
{
	/**
	 * The unit as is on the invoice - BUC/HOUR/SUBSCRIPTION ETC
	 */
    private string $documentPositionUnits;

	/**
	 * The coresponding code from the list of units
	 * @link https://docs.peppol.eu/poacc/billing/3.0/codelist/UNECERec20/
	 */
	private string $documentPositionDescriptions = 'XZZ'; // Mutually defined ie - custom


    private static array $schema = [
        'documentPositionUnits' => 'string',
        'documentPositionDescriptions' => 'string',
    ];

    /**
     * @param array{documentPositionUnits: string, documentPositionUnitsCode: string} $item
     *
     * @return EFacturaDocumentPositionUnitsAttribute
     * @throws InvalidInvoiceItemException
     */
    public static function fromArray(array $item): EFacturaDocumentPositionUnitsAttribute
    {
        $result = new EFacturaDocumentPositionUnitsAttribute();

        foreach ($result->validate($item) as $field => $value) {
            $result->{$field} = $value;
        }

        return $result;
    }

    public function toPayload(): array
    {
        return [
            'documentPositionUnits' => $this->documentPositionUnits,
			'documentPositionDescriptions' => $this->documentPositionDescriptions,
        ];
    }

    /**
     * @throws InvalidEFacturaDocumentPositionUnitAttribute
     */
    private function validate(array $data): array
    {
        return SuperTinyArrayValidator::createFor('efacturaDocumentPositionUnitsAttribute')
            ->onErrorThrow(InvalidEFacturaDocumentPositionUnitAttribute::class)
            ->validate(self::$schema, $data);
    }
}
