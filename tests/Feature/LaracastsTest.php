<?php

uses()->group('laracasts');

it('can validate an email', function () {
    $rule = new \App\Rules\IsValidEmailAddress();

    expect($rule->passes('email', 'me@you.com'))->toBeTrue();
});

it('throws an exception if the value is not a string', function () {
    $rule = new \App\Rules\IsValidEmailAddress();

    $rule->passes('email', 1);
})->throws(InvalidArgumentException::class, 'The value must be a string!');
