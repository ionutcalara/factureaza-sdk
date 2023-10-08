<?php

declare(strict_types=1);

namespace Konekt\Factureaza\Requests;

use Konekt\Factureaza\Contracts\Mutation;

class SubmitInvoiceToAnafAsEFactura extends InvoiceAsEFactura implements Mutation
{

	public function fields(): array
	{
		return [
			'efacturaTransaction'
		];
	}

	public function operation(): string
	{
		return 'submitInvoiceEfacturaUbl';
	}

}
