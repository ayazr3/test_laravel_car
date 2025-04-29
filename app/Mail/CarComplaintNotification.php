<?php

namespace App\Mail;

use App\Models\Review;
use App\Models\Car;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CarComplaintNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $review;
    public $car;

    public function __construct(Review $review, Car $car)
    {
        $this->review = $review;
        $this->car = $car;
    }

    public function build()
    {
        return $this->subject('شكوى جديدة على سيارتك - ' . $this->car->brand . ' ' . $this->car->model)
                    ->view('emails.car_complaint');
    }
}
