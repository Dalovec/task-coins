<x-mail::message>
# Introduction

Hi,

Your watch dog caught a price change on {{ $coin->name }}.

The price was {{ $dog->set_price }} and is now {{ $coin->current_price }} with a difference of {{ $dog->set_price - $coin->current_price }}.

Your watch dog will still be checking the price for another changes.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
