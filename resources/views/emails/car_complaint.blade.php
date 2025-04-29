@component('mail::message')
# شكوى جديدة على سيارتك

لقد تلقيت شكوى بخصوص سيارتك:
**{{ $car->brand }} {{ $car->model }}**

**محتوى الشكوى:**
{{ $review->note }}

**بريد المرسل:**
{{ $review->email }}

**تاريخ الإرسال:**
{{ $review->created_at}}

@component('mail::button', ['url' => route('cars.details', $car->id)])
عرض السيارة
@endcomponent

شكرًا لك،
{{ config('app.name') }}
@endcomponent
