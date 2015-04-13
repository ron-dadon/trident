<?php
class Invoice_Entity extends Trident_Abstract_Entity
{
	public $id;
	public $quote;
	public $notes;
	public $creation_date;
	public $receipt;
	public $tax;
	public $delete;
}