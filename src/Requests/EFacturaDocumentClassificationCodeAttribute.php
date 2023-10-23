<?php

declare(strict_types=1);

namespace Konekt\Factureaza\Requests;

use Konekt\Factureaza\Exceptions\InvalidEFacturaClassificationCodeAttribute;
use Konekt\Factureaza\Exceptions\InvalidEFacturaDocumentPositionUnitAttribute;
use Konekt\Factureaza\Validation\SuperTinyArrayValidator;

class EFacturaDocumentClassificationCodeAttribute
{
	/**
	 * Line item id
	 */
	private string $documentPositionId;

	/**
	 * The corresponding for the vocabulary
	 * @link https://docs.peppol.eu/poacc/billing/3.0/codelist/UNCL7143/
	 */
	private string $schemeIdentifier = 'STI'; // code for CPV - Common procurement vocabulary

	/**
	 * The code from the vocabulary
	 * EG: 72416000-9`  -> Application service providers
	 */
	private string $classificationCode;


	private static array $schema = [
		'documentPositionId' => 'string*',
		'schemeIdentifier' => 'string',
		'classificationCode' => 'string',
	];

	/**
	 * @param array{documentPositionId: string, schemeIdentifier: string, classificationCode: string} $item
	 *
	 * @return EFacturaDocumentPositionUnitsAttribute
	 * @throws InvalidEFacturaDocumentPositionUnitAttribute
	 */
	public static function fromArray(array $item): EFacturaDocumentClassificationCodeAttribute
	{
		$result = new EFacturaDocumentClassificationCodeAttribute();

		foreach($result->validate($item) as $field => $value) {
			$result->{$field} = $value;
		}

		return $result;
	}

	public function toPayload(): array
	{
		return [
			'documentPositionId' => $this->documentPositionId,
			'schemeIdentifier' => $this->schemeIdentifier,
			'classificationCode' => $this->classificationCode
		];
	}

	/**
	 * @throws InvalidEFacturaClassificationCodeAttribute
	 */
	private function validate(array $data): array
	{
		return SuperTinyArrayValidator::createFor('efacturaDocumentClasificationCodeAttribute')
			->onErrorThrow(InvalidEFacturaClassificationCodeAttribute::class)
			->validate(self::$schema, $data);
	}
}
