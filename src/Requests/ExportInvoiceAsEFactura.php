<?php

declare(strict_types=1);

namespace Konekt\Factureaza\Requests;

use Konekt\Factureaza\Contracts\Mutation;

class ExportInvoiceAsEFactura extends InvoiceAsEFactura implements Mutation
{

	public function fields(): array
	{
		return [
			'xml'
		];
	}

	public function operation(): string
	{
		return 'exportInvoiceEfacturaUbl';
	}

}
