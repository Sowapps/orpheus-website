<?php

function getEnumValues() {
	return array('first', 'second', 'third');
}

$ed = EntityDescriptor::load('entity_tests');

// Validator
$values = array(
	array('a_number',	'125'),
	array('a_number',	'-125'),
	array('a_string',	'short string'),
	array('a_string',	'This is a test for a very long string'),
	array('a_date',		'25/12/1987'),
	array('a_date',		'25/12/1987 12:50:12'),
	array('a_datetime',	'25/12/1987'),
	array('a_datetime',	'25/12/1987 12:50:12'),
	array('an_email',	'test@domain.com'),
	array('an_email',	'128.14967.16'),
	array('a_password',	'test'),
	array('a_password',	'password'),
	array('a_phone',	'0123456789'),
	array('a_phone',	'+331.23.45.67.89'),
	array('a_phone',	'invalid number'),
	array('an_url',		'http://zerofraisdecourtage.fr'),
	array('an_url',		'378954156546'),
	array('an_ip',		'127.0.0.1'),
	array('a_ref',		'9874'),
	array('an_enum',	'second'),
	array('an_enum',	'zero'),
);
foreach( $values as $a ) {
	try {
		echo $a[0].' => '.$a[1].' : ';
		$ed->validateFieldValue($a[0], $a[1]);
		text('OK ('.$a[1].').');
	} catch( InvalidFieldException $e ) {
		text($e->getMessage());
	} catch( Exception $e ) {
		text($e);
	}
}
