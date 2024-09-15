<x-mail::message>
# Introduction

Hi,

**{{ $coin->name }}** price has changed!

Previous price: `{{ $dog->set_price }}€`

Current price: `{{ $coin->current_price }}€`

Change: `{{ $dog->set_price - $coin->current_price }}€`

We've removed your watch dog because the price has changed by more than {{ $dog->change }}%.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
